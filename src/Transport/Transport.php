<?php
/*
    Copyright (C) 2012 Vernon Systems Limited

    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"),
    to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
    and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
    WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

namespace EHive\Transport;

use EHive\Domain\BadRequestMessage;
use EHive\Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;
use EHive\Cache\NullCache;

class Transport implements TransportInterface, LoggerAwareInterface, CacheAwareInterface
{
    const API_URL = "https://ehive.com/api";
    const GRANT_TYPE_AUTHORIZATION_CODE = "authorization_code";

    const OAUTH_TOKEN_ENDPOINT_PATH = '/oauth2/v2/token';
    const OAUTH_AUTHORIZATION_ENDPOINT_PATH = '/oauth2/v2/authorize';

    const OAUTH_TOKEN_MISSING = 'OAuth Token is missing after the server said it has returned it. '.
                                'This is a fatal error  and should be reported at http://forum.ehive.com';
    const RESOURCE_NOT_FOUND = 'Resource Not Found. Please check that your request URL is valid';
    const EHIVE_DOWN = 'eHive is currently down for a short period of maintenance. HTTP response code: 503';
    const UNEXPECTED_ERROR = 'An unexpected error has occured while trying to access the eHive API. HTTP response code: ';

    /**
     * @var string
     */
    private $apiUrl = self::API_URL;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $trackingId;

    /**
     * @var string
     */
    private $oauthToken;

    /**
     * @var callable
     */
    private $oauthTokenCallback;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * Cache time to live, defaults to 1 hour.
     * @var int
     */
    private $cacheTtl = 3600;

    /**
     * @var bool
     */
    private $cacheEnabled = true;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var int
     */
    private $retryAttempts = 0;

    /**
     * @var bool
     */
    private $apiAccessByCredentials = false;

    /**
     * @param string|null   $clientId           eHive API key client Id
     * @param string|null   $clientSecret       eHive API key client secret
     * @param string|null   $trackingId         eHive API key tracking Id.
     * @param string|null   $oauthToken         The OAuthToken returned by a previous request API request.
     * @param callable|null $oauthTokenCallback A function that takes a single string parameter for a returned OAuth
     *                                          Token.
     *
     * Example of $oauthTokenCallback:
     * function oauthTokenCallback(OauthCredentials $oauthCredentials) {
     *     // persist the returned OAuth Credentials for reuse with the next instantiation of an ApiClient class.
     * }
     *
     */
    public function __construct(
        $clientId = null,
        $clientSecret = null,
        $trackingId = null,
        $oauthToken = null,
        callable $oauthTokenCallback = null
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->trackingId = $trackingId;
        $this->oauthToken = $oauthToken;
        $this->oauthTokenCallback = $oauthTokenCallback;
        $this->cache = new NullCache();
        $this->logger = new NullLogger();

        if (is_null($clientId) === false and is_null($clientSecret) === false) {
            $this->apiAccessByCredentials = true;
        } else {
            $this->apiAccessByCredentials = false;
        }
    }

    public function setOauthToken($oauthToken)
    {
        $this->oauthToken = $oauthToken;

        return $this;
    }

    public function setOauthTokenCallback(callable $oauthTokenCallback)
    {
        $this->oauthTokenCallback = $oauthTokenCallback;

        return $this;
    }

    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;

        return $this;
    }

    /**
     * Set a PSR-16 compatible cache object
     *
     * @param CacheInterface $cache
     *
     * @return $this
     */
    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @param int $cacheTtl
     *
     * @return $this
     */
    public function setCacheTtl($cacheTtl)
    {
        $this->cacheTtl = $cacheTtl;

        return $this;
    }

    /**
     * @param bool $cacheEnabled
     *
     * @return $this
     */
    public function setCacheEnabled($cacheEnabled)
    {
        $this->cacheEnabled = $cacheEnabled;

        return $this;
    }

    /**
     * Set a PSR-3 compatible logger object
     *
     * @param LoggerInterface $logger
     *
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param string $path
     * @param string $queryString
     *
     * @return array|mixed|null|string
     * @throws Exception\ApiException
     * @throws Exception\UnauthorizedException
     */
    public function get($path, $queryString = '')
    {
        // Look in the cache first.
        $key = $this->cacheKey($path, $queryString);
        if ($this->cache->has($key) and $this->cacheEnabled) {
            return $this->cache->get($key);
        }

        $uri = $this->apiUrl . $path;

        $completeUrl = $this->createUrl($uri, $queryString);

        $oauthCredentials = $this->getOauthCredentials();

        $headers = [];

        $headers[] = 'Content-Type: application/json';

        if ($this->apiAccessByCredentials) {
            $headers[] = 'Authorization: Basic ' . $oauthCredentials->oauthToken;
            $headers[] = 'Client-Id: ' . $oauthCredentials->clientId;
            $headers[] = 'Grant-Type: ' . self::GRANT_TYPE_AUTHORIZATION_CODE;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $completeUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->logger->error("curl error: " . curl_errno($ch) . " - " . curl_error($ch), [
                'url' => $completeUrl,
            ]);
        }

        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        switch ($httpResponseCode) {
            case 200:
                $json = json_decode($response);

                if ($this->cacheEnabled) {
                    $this->cache->set($this->cacheKey($path, $queryString), $json, $this->cacheTtl);
                }

                return $json;

            case 401:
                if ($this->apiAccessByCredentials && $this->retryAttempts < 3) {
                    $oauthCredentials = $this->getAuthenticated();

                    if (is_null($oauthCredentials->oauthToken)) {
                        throw new Exception\ApiException(self::OAUTH_TOKEN_MISSING);
                    }

                    $this->retryAttempts = $this->retryAttempts + 1;

                    return $this->get($path, $queryString);
                } else {
                    $json = json_decode($response);

                    throw new Exception\UnauthorizedException(new Exception\StatusMessage($json));
                }
                break;

            default:
                $this->handleErrorStatus($httpResponseCode, $response);
        }
    }

    public function post($path, $content = '')
    {
        $uri = $this->apiUrl . $path;

        $completeUrl = $this->createUrl($uri);

        $oauthCredentials = $this->getOauthCredentials();

        if ($this->retryAttempts == 0) {
            $content = json_encode($content);
        }

        $headers = [
            'Content-Type: application/json',
            'Content-Length:' . strlen($content),
            'Authorization: Basic ' . $oauthCredentials->oauthToken,
            'Client-Id: ' . $oauthCredentials->clientId,
            'Grant-Type: ' . self::GRANT_TYPE_AUTHORIZATION_CODE,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $completeUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->logger->error("curl error: " . curl_errno($ch) . " - " . curl_error($ch), [
                'url' => $completeUrl,
                'content' => $content,
            ]);
        }

        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        switch ($httpResponseCode) {
            case 200:
                return json_decode($response);

            case 401:
                if ($this->retryAttempts < 3) {
                    $oauthCredentials = $this->getAuthenticated();

                    if (is_null($oauthCredentials->oauthToken)) {
                        throw new Exception\ApiException(self::OAUTH_TOKEN_MISSING);
                    }

                    $this->retryAttempts = $this->retryAttempts + 1;

                    return $this->post($path, $content);
                } else {
                    $json = json_decode($response);

                    throw new Exception\UnauthorizedException(new Exception\StatusMessage($json));
                }
                break;

            default:
                $this->handleErrorStatus($httpResponseCode, $response);
        }
    }

    public function put($path, $content = '')
    {
        $uri = $this->apiUrl . $path;

        $completeUrl = $this->createUrl($uri);

        $oauthCredentials = $this->getOauthCredentials();

        if ($this->retryAttempts == 0) {
            $content = json_encode($content);
        }

        $headers = [
            'Content-Type: application/json',
            'Content-Length:' . strlen($content),
            'Authorization: Basic ' . $oauthCredentials->oauthToken,
            'Client-Id: ' . $oauthCredentials->clientId,
            'Grant-Type: ' . self::GRANT_TYPE_AUTHORIZATION_CODE,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $completeUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->logger->error("curl error: " . curl_errno($ch) . " - " . curl_error($ch), [
                'url' => $completeUrl,
                'content' => $content,
            ]);
        }

        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        switch ($httpResponseCode) {
            case 200:
                return json_decode($response);

            case 401:
                if ($this->retryAttempts < 3) {
                    $oauthCredentials = $this->getAuthenticated();

                    if (is_null($oauthCredentials->oauthToken)) {
                        throw new Exception\ApiException(self::OAUTH_TOKEN_MISSING);
                    }

                    $this->retryAttempts = $this->retryAttempts + 1;

                    return $this->post($path, $content);
                } else {
                    $json = json_decode($response);

                    throw new Exception\UnauthorizedException(new Exception\StatusMessage($json));
                }

            default:
                $this->handleErrorStatus($httpResponseCode, $response);
        }
    }

    public function delete($path, $queryString = '')
    {
        $uri = $this->apiUrl . $path;

        $completeUrl = $this->createUrl($uri, $queryString);

        $oauthCredentials = $this->getOauthCredentials();
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . $oauthCredentials->oauthToken,
            'Client-Id: ' . $oauthCredentials->clientId,
            'Grant-Type: ' . self::GRANT_TYPE_AUTHORIZATION_CODE,
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_URL, $completeUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->logger->error("curl error: " . curl_errno($ch) . " - " . curl_error($ch), [
                'url' => $completeUrl,
                'queryString' => $queryString,
            ]);
        }

        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        switch ($httpResponseCode) {
            case 200:
                return json_decode($response);

            case 401:
                if ($this->retryAttempts < 3) {
                    $oauthCredentials = $this->getAuthenticated();

                    if (is_null($oauthCredentials->oauthToken)) {
                        throw new Exception\ApiException(self::OAUTH_TOKEN_MISSING);
                    }

                    $this->retryAttempts++;

                    return $this->delete($path, $queryString);
                } else {
                    $json = json_decode($response);

                    throw new Exception\UnauthorizedException(new Exception\StatusMessage($json));
                }

            default:
                curl_close($ch);
                $this->handleErrorStatus($httpResponseCode, $response);
                break;
        }
    }

    /**
     * @throws Exception\ApiException
     * @throws Exception\FatalServerException
     * @throws Exception\ForbiddenException
     * @throws Exception\NotFoundException
     * @throws Exception\UnauthorizedException
     * @return OauthCredentials
     */
    private function getAuthenticated()
    {
        $authorisationEndpointUrl = $this->apiUrl . self::OAUTH_AUTHORIZATION_ENDPOINT_PATH;
        $tokenEndpointUrl = $this->apiUrl . self::OAUTH_TOKEN_ENDPOINT_PATH;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $authorisationEndpointUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: OAuth',
            'Client-Id: ' . $this->clientId,
            'Client-Secret: ' . $this->clientSecret,
            'Grant-Type: client_credentials',
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->logger->error("curl error: " . curl_errno($ch) . " - " . curl_error($ch), [
                'url' => $authorisationEndpointUrl,
            ]);
        }

        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        switch ($httpResponseCode) {
            case 303:
                $header = substr($response, 0, $headerSize);

                $headersArray = explode("\n", $header);
                $headers = [];

                foreach ($headersArray as $header) {
                    $headerParts = explode(": ", $header);
                    $headers[$headerParts[0]] = $header;
                }

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $tokenEndpointUrl);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Content-Type: application/x-www-form-urlencoded",
                    str_replace("\r", "", $headers["Access-Grant"]),
                    str_replace("\r", "", $headers["Authorization"]),
                    str_replace("\r", "", $headers["Client-Id"]),
                    str_replace("\r", "", $headers["Grant-Type"]),
                ]);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $response = curl_exec($ch);

                if (curl_errno($ch)) {
                    $this->logger->error("curl error: " . curl_errno($ch) . " - " . curl_error($ch), [
                        'url' => $tokenEndpointUrl,
                    ]);
                }

                $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                switch ($httpResponseCode) {
                    case 200:
                        $json = json_decode($response);
                        $oauthCredentials = $this->asOauthCredentials($json);

                        if (is_callable($this->oauthTokenCallback)) {
                            call_user_func($this->oauthTokenCallback, $oauthCredentials);
                        }

                        $this->oauthToken = $oauthCredentials->oauthToken;

                        return $oauthCredentials;

                    default:
                        $this->handleErrorStatus($httpResponseCode, $response);
                        break;
                }

                break;

            default:
                $this->handleErrorStatus($httpResponseCode, $response);
                break;
        }
    }

    /**
     * @param int    $statusCode
     * @param string $response
     *
     * @throws Exception\ApiException
     * @throws Exception\BadRequestException
     * @throws Exception\FatalServerException
     * @throws Exception\ForbiddenException
     * @throws Exception\NotFoundException
     * @throws Exception\UnauthorizedException
     */
    private function handleErrorStatus($statusCode, $response = null)
    {
        switch ($statusCode) {
            case 400:
                $json = json_decode($response);
                $badRequestMessage = new BadRequestMessage($json);

                if (!empty($badRequestMessage->requestMessage) or !empty($badRequestMessage->requestFields)) {
                    throw new Exception\BadRequestException(
                        $badRequestMessage->requestMessage,
                        $badRequestMessage->requestFields
                    );
                }

                throw new Exception\BadRequestException('Bad Request');

            case 403:
                $json = json_decode($response);
                throw new Exception\ForbiddenException(new Exception\StatusMessage($json));

            case 404:
                throw new Exception\NotFoundException(self::RESOURCE_NOT_FOUND);

            case 500:
                $json = json_decode($response);
                throw new Exception\FatalServerException(new Exception\StatusMessage($json));

            case 503:
                throw new Exception\ApiException(self::EHIVE_DOWN);

            default:
                throw new Exception\ApiException(self::UNEXPECTED_ERROR . ' ' . $statusCode);
        }
    }

    private function getOauthCredentials()
    {
        return new OauthCredentials($this->clientId, $this->clientSecret, $this->oauthToken);
    }

    private function createUrl($uri, $queryString = '')
    {
        if (!empty($this->trackingId)) {
            if (empty($queryString)) {
                $uri .= '?trackingId=' . $this->trackingId;
            } else {
                $uri .= '?' . $queryString . '&trackingId=' . $this->trackingId;
            }
        }

        return $uri;
    }

    private function asOauthCredentials($json)
    {
        $oauthCredentials = new OauthCredentials();
        $oauthCredentials->clientId = isset($json->clientId) ? $json->clientId : null;
        $oauthCredentials->clientSecret = isset($json->clientSecret) ? $json->clientSecret : null;
        $oauthCredentials->oauthToken = isset($json->oauthToken) ? $json->oauthToken : null;

        return $oauthCredentials;
    }

    private function cacheKey($path, $queryString)
    {
        return md5($path . $queryString);
    }
}
