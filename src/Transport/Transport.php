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
use Memcache;

class Transport implements TransportInterface
{
    const HTTPS_PROTOCOL = 'https://';
    const API_URL = "ehive.com/api";
    const GRANT_TYPE_AUTHORIZATION_CODE = "authorization_code";

    const OAUTH_TOKEN_ENDPOINT_PATH = '/oauth2/v2/token';
    const OAUTH_AUTHORIZATION_ENDPOINT_PATH = '/oauth2/v2/authorize';

    const OAUTH_TOKEN_MISSING = 'OAuth Token is missing after the server said it has vended it. This is a fatal error and should be reported at http://forum.ehive.com.';
    const RESOURCE_NOT_FOUND = 'Resource Not Found. Please check that your request URL is valid.';
    const EHIVE_DOWN = 'eHive is currently down for a short period of maintenance. HTTP response code: 503';
    const UNEXPECTED_ERROR = 'An unexpected error has occured while trying to access the eHive API. HTTP response code: ';

    private $apiUrl;
    private $clientId;
    private $clientSecret;
    private $trackingId;

    private $oauthToken;
    private $oauthTokenCallback;

    private $memcachedServers;
    private $memcacheExpiry;
    private $memcache;

    private $retryAttempts = 0;

    private $apiAccessByCredentials = false;

    /**
     * @param string    $clientId        eHive API key client Id
     * @param string    $clientSecret    eHive API key client secret
     * @param string    $trackingId      eHive API key tracking Id.
     * @param string    $oauthToken      The OAuthToken vendered by a previous request API request.
     * @param callable  $oauthTokenCallback A function that takes a single String parameter for a vendered OAuth Token.
     * @param array     $memcachedServers  array of hosts and ports for Memcached services. When null memcache is disabled.
     * @param int       $memcacheExpiry  cache expiry time in seconds.
     *
     * Example of $oauthTokenCallback:
     *      function oauthTokenCallback($oauthToken) {
     *         // persist the vendored oauthToken for reuse with the next instantiation of an ApiClient class.
     *      }
     *
     * Example of $memcachedServers:
     *      Memcached on the same server - array('localhost:11211')
     *      Memcached distributed on two servers - array('192.168.1.4:11211', '192.168.1.5:11211')
     *
     */
    public function __construct(
        $clientId = '',
        $clientSecret = '',
        $trackingId = '',
        $oauthToken = '',
        $oauthTokenCallback = null,
        $memcachedServers = null,
        $memcacheExpiry = 300
    ) {

        $this->apiUrl = self::API_URL;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->trackingId = $trackingId;
        $this->oauthToken = $oauthToken;
        $this->oauthTokenCallback = $oauthTokenCallback;

        $this->memcachedServers = $memcachedServers;
        $this->memcacheExpiry = $memcacheExpiry;

        if ((is_null($clientId) == false) && (is_null($clientSecret) == false)) {
            $this->apiAccessByCredentials = true;
        } else {
            $this->apiAccessByCredentials = false;
        }
    }

    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    public function setTrackingId($trackingId)
    {
        $this->trackingId = $trackingId;

        return $this;
    }

    public function setOauthToken($oauthToken)
    {
        $this->oauthToken = $oauthToken;

        return $this;
    }

    public function setOauthTokenCallback($oauthTokenCallback)
    {
        $this->oauthTokenCallback = $oauthTokenCallback;

        return $this;
    }

    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;

        return $this;
    }

    public function get($path, $queryString = '', $useCache = false)
    {
        // Look in the cache first.
        if (!is_null($this->memcachedServers) && $useCache === true) {
            $this->memcache = new Memcache();

            for ($r = 0; $r < count($this->memcachedServers); $r++) {
                $hostport = explode(":", $this->memcachedServers[$r]);

                $host = $hostport[0];
                $port = intval($hostport[1]);

                $this->memcache->addServer($host, $port);
            }

            $cachedValue = $this->memcache->get($this->memcacheKey($path, $queryString));
            if (!$cachedValue === false) {
                $this->memcache->close();

                return $cachedValue;
            }
        }

        $ch = curl_init();

        $uri = $this->apiUrl . $path;

        $completeUrl = $this->createUrl($uri, $queryString);

        $oauthCredentials = $this->getOauthCredentials();

        curl_setopt($ch, CURLOPT_URL, $completeUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $headers = [];

        $headers[] = 'Content-Type: application/json';

        if ($this->apiAccessByCredentials) {
            $headers[] = 'Authorization: Basic ' . $oauthCredentials->oauthToken;
            $headers[] = 'Client-Id: ' . $oauthCredentials->clientId;
            $headers[] = 'Grant-Type: ' . self::GRANT_TYPE_AUTHORIZATION_CODE;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log("curl error: " . curl_errno($ch) . " - " . curl_error($ch));
        }

        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        switch ($httpResponseCode) {
            case 200:
                $json = json_decode($response);
                curl_close($ch);

                // Add result to the cache then close.
                if (!is_null($this->memcachedServers) && $useCache === true) {
                    $this->memcache->add($this->memcacheKey($path, $queryString), $json, false, $this->memcacheExpiry);
                    $this->memcache->close();
                }

                return $json;
                break;

            case 400:
                $json = json_decode($response);
                curl_close($ch);

                if (!is_null($this->memcachedServers) && $useCache === true) {
                    $this->memcache->close();
                }


                throw new Exception\BadRequestException($json);
                break;

            case 401:
                curl_close($ch);
                if (!is_null($this->memcachedServers) && $useCache === true) {
                    $this->memcache->close();
                }

                if ($this->apiAccessByCredentials && $this->retryAttempts < 3) {
                    $oauthCredentials = $this->getAuthenticated();

                    if (is_null($oauthCredentials->oauthToken)) {
                        throw new Exception\ApiException(self::OAUTH_TOKEN_MISSING);
                    }

                    $this->retryAttempts = $this->retryAttempts + 1;

                    $json = $this->get($path, $queryString, $useCache);

                    return $json;
                } else {
                    $json = json_decode($response);
                    curl_close($ch);


                    $ehiveStatusMessage = new Exception\StatusMessage($json);

                    throw new Exception\UnauthorizedException($ehiveStatusMessage->toString());
                }
                break;

            case 403:
                $json = json_decode($response);
                curl_close($ch);
                if (!is_null($this->memcachedServers) && $useCache === true) {
                    $this->memcache->close();
                }


                $ehiveStatusMessage = new Exception\StatusMessage($json);

                throw new Exception\ForbiddenException($ehiveStatusMessage->toString());
                break;

            case 404:
                curl_close($ch);
                if (!is_null($this->memcachedServers) && $useCache === true) {
                    $this->memcache->close();
                }

                throw new Exception\NotFoundException(self::RESOURCE_NOT_FOUND);
                break;

            case 500:
                $json = json_decode($response);
                curl_close($ch);
                if (!is_null($this->memcachedServers) && $useCache === true) {
                    $this->memcache->close();
                }


                $ehiveStatusMessage = new Exception\StatusMessage($json);

                throw new Exception\FatalServerException($ehiveStatusMessage->toString());
                break;

            case 503:
                curl_close($ch);
                if (!is_null($this->memcachedServers) && $useCache === true) {
                    $this->memcache->close();
                }

                throw new Exception\ApiException(self::EHIVE_DOWN);
                break;

            default:
                curl_close($ch);
                if (!is_null($this->memcachedServers) && $useCache === true) {
                    $this->memcache->close();
                }

                throw new Exception\ApiException(self::UNEXPECTED_ERROR . $httpResponseCode);
                break;
        }
    }

    public function post($path, $content = '')
    {
        $ch = curl_init();

        $uri = $this->apiUrl . $path;

        $completeUrl = $this->createUrl($uri);

        $oauthCredentials = $this->getOauthCredentials();

        if ($this->retryAttempts == 0) {
            $content = json_encode($content);
        }

        curl_setopt($ch, CURLOPT_URL, $completeUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $headers = [
            'Content-Type: application/json',
            'Content-Length:' . strlen($content),
            'Authorization: Basic ' . $oauthCredentials->oauthToken,
            'Client-Id: ' . $oauthCredentials->clientId,
            'Grant-Type: ' . self::GRANT_TYPE_AUTHORIZATION_CODE,
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log("curl error: " . curl_errno($ch) . " - " . curl_error($ch));
        }

        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        switch ($httpResponseCode) {
            case 200:
                $json = json_decode($response);
                curl_close($ch);
                break;

            case 400:
                $json = json_decode($response);
                curl_close($ch);


                throw new Exception\BadRequestException($json);
                break;

            case 401:
                curl_close($ch);

                if ($this->retryAttempts < 3) {
                    $oauthCredentials = $this->getAuthenticated();

                    if (is_null($oauthCredentials->oauthToken)) {
                        throw new Exception\ApiException(self::OAUTH_TOKEN_MISSING);
                    }

                    $this->retryAttempts = $this->retryAttempts + 1;
                    $json = $this->post($path, $content);
                } else {
                    $json = json_decode($response);
                    curl_close($ch);


                    $ehiveStatusMessage = new Exception\StatusMessage($json);

                    throw new Exception\UnauthorizedException($ehiveStatusMessage->toString());
                }
                break;

            case 403:
                $json = json_decode($response);
                curl_close($ch);


                $ehiveStatusMessage = new Exception\StatusMessage($json);

                throw new Exception\ForbiddenException($ehiveStatusMessage->toString());
                break;

            case 404:
                curl_close($ch);
                throw new Exception\NotFoundException(self::RESOURCE_NOT_FOUND);
                break;

            case 500:
                $json = json_decode($response);
                curl_close($ch);


                $ehiveStatusMessage = new Exception\StatusMessage($json);

                throw new Exception\FatalServerException($ehiveStatusMessage->toString());
                break;

            case 503:
                throw new Exception\ApiException(self::EHIVE_DOWN);
                break;

            default:
                curl_close($ch);
                throw new Exception\ApiException(self::UNEXPECTED_ERROR . $httpResponseCode);
                break;
        }

        return $json;
    }

    public function put($path, $content = '')
    {
        $ch = curl_init();

        $uri = $this->apiUrl . $path;

        $completeUrl = $this->createUrl($uri);

        $oauthCredentials = $this->getOauthCredentials();

        if ($this->retryAttempts == 0) {
            $content = json_encode($content);
        }

        curl_setopt($ch, CURLOPT_URL, $completeUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        //curl_setopt($ch, CURLOPT_PUT, 1);

        curl_setopt($ch, CURLOPT_HEADER, 0);


        $headers = [
            'Content-Type: application/json',
            'Content-Length:' . strlen($content),
            'Authorization: Basic ' . $oauthCredentials->oauthToken,
            'Client-Id: ' . $oauthCredentials->clientId,
            'Grant-Type: ' . self::GRANT_TYPE_AUTHORIZATION_CODE,
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log("curl error: " . curl_errno($ch) . " - " . curl_error($ch));
        }

        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        switch ($httpResponseCode) {
            case 200:
                $json = json_decode($response);
                curl_close($ch);
                break;

            case 400:
                $json = json_decode($response);
                $badRequestMessage = new BadRequestMessage($json);

                curl_close($ch);


                throw new Exception\BadRequestException(
                    $badRequestMessage->requestMessage,
                    $badRequestMessage->requestFields
                );
                break;

            case 401:
                curl_close($ch);

                if ($this->retryAttempts < 3) {
                    $oauthCredentials = $this->getAuthenticated();

                    if (is_null($oauthCredentials->oauthToken)) {
                        throw new Exception\ApiException(self::OAUTH_TOKEN_MISSING);
                    }

                    $this->retryAttempts = $this->retryAttempts + 1;
                    $json = $this->post($path, $content);
                } else {
                    $json = json_decode($response);
                    curl_close($ch);


                    $ehiveStatusMessage = new Exception\StatusMessage($json);

                    throw new Exception\UnauthorizedException($ehiveStatusMessage->toString());
                }
                break;

            case 403:
                $json = json_decode($response);
                curl_close($ch);


                $ehiveStatusMessage = new Exception\StatusMessage($json);

                throw new Exception\ForbiddenException($ehiveStatusMessage->toString());
                break;

            case 404:
                curl_close($ch);
                throw new Exception\NotFoundException(self::RESOURCE_NOT_FOUND);
                break;

            case 500:
                $json = json_decode($response);
                curl_close($ch);


                $ehiveStatusMessage = new Exception\StatusMessage($json);

                throw new Exception\FatalServerException($ehiveStatusMessage->toString());
                break;

            case 503:
                throw new Exception\ApiException(self::EHIVE_DOWN);
                break;

            default:
                curl_close($ch);
                throw new Exception\ApiException(self::UNEXPECTED_ERROR . $httpResponseCode);
                break;
        }

        return $json;
    }

    public function delete($path, $queryString = '')
    {
        $ch = curl_init();

        $uri = $this->apiUrl . $path;

        $completeUrl = $this->createUrl($uri, $queryString);

        $oauthCredentials = $this->getOauthCredentials();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_URL, $completeUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . $oauthCredentials->oauthToken,
            'Client-Id: ' . $oauthCredentials->clientId,
            'Grant-Type: ' . self::GRANT_TYPE_AUTHORIZATION_CODE,
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log("curl error: " . curl_errno($ch) . " - " . curl_error($ch));
        }

        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        switch ($httpResponseCode) {
            case 200:
                $json = json_decode($response);
                curl_close($ch);
                break;

            case 401:
                curl_close($ch);

                if ($this->retryAttempts < 3) {
                    $oauthCredentials = $this->getAuthenticated();

                    if (is_null($oauthCredentials->oauthToken)) {
                        throw new Exception\ApiException(self::OAUTH_TOKEN_MISSING);
                    }

                    $this->retryAttempts = $this->retryAttempts + 1;
                    $json = $this->delete($path, $queryString);
                } else {
                    $json = json_decode($response);
                    curl_close($ch);


                    $ehiveStatusMessage = new Exception\StatusMessage($json);

                    throw new Exception\UnauthorizedException($ehiveStatusMessage->toString());
                }
                break;

            case 403:
                $json = json_decode($response);
                curl_close($ch);

                
                $ehiveStatusMessage = new Exception\StatusMessage($json);

                throw new Exception\ForbiddenException($ehiveStatusMessage->toString());
                break;

            case 404:
                curl_close($ch);
                throw new Exception\NotFoundException(self::RESOURCE_NOT_FOUND);
                break;

            case 500:
                $json = json_decode($response);
                curl_close($ch);


                $ehiveStatusMessage = new Exception\StatusMessage($json);

                throw new Exception\FatalServerException($ehiveStatusMessage->toString());
                break;

            case 503:
                curl_close($ch);
                throw new Exception\ApiException(self::EHIVE_DOWN);
                break;

            default:
                curl_close($ch);
                throw new Exception\ApiException(self::UNEXPECTED_ERROR . $httpResponseCode);
                break;
        }

        return $json;
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
        $tokenEndpointUrl = self::HTTPS_PROTOCOL . $this->apiUrl . self::OAUTH_TOKEN_ENDPOINT_PATH;
        $authorizaitonEndpointUrl = self::HTTPS_PROTOCOL . $this->apiUrl . self::OAUTH_AUTHORIZATION_ENDPOINT_PATH;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $authorizaitonEndpointUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

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
            error_log("curl error: " . curl_errno($ch) . " - " . curl_error($ch));
        }

        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        switch ($httpResponseCode) {
            case 303:
                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $header = substr($response, 0, $header_size);

                $headersArray = explode("\n", $header);
                $headers = [];

                foreach ($headersArray as $header) {
                    $headerParts = explode(": ", $header);
                    $headers[$headerParts[0]] = $header;
                }

                curl_close($ch);

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
                    error_log("curl error: " . curl_errno($ch) . " - " . curl_error($ch));
                }

                $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                switch ($httpResponseCode) {
                    case 200:
                        $json = json_decode($response);

                        curl_close($ch);

                        $oauthCredentials = $this->asOauthCredentials($json);

                        array_map($this->oauthTokenCallback, [$oauthCredentials->oauthToken]);

                        $this->oauthToken = $oauthCredentials->oauthToken;

                        return $oauthCredentials;

                    case 401:
                        $json = json_decode($response);
                        curl_close($ch);

                        $ehiveStatusMessage = new Exception\StatusMessage($json);

                        throw new Exception\UnauthorizedException($ehiveStatusMessage->toString());
                        break;

                    case 403:
                        $json = json_decode($response);
                        curl_close($ch);

                        $ehiveStatusMessage = new Exception\StatusMessage($json);

                        throw new Exception\ForbiddenException($ehiveStatusMessage->toString());
                        break;

                    case 404:
                        curl_close($ch);
                        throw new Exception\NotFoundException(self::RESOURCE_NOT_FOUND);
                        break;

                    case 500:
                        $json = json_decode($response);
                        curl_close($ch);

                        $ehiveStatusMessage = new Exception\StatusMessage($json);

                        throw new Exception\FatalServerException($ehiveStatusMessage->toString());
                        break;

                    case 503:
                        curl_close($ch);
                        throw new Exception\ApiException(self::EHIVE_DOWN);
                        break;

                    default:
                        curl_close($ch);
                        throw new Exception\ApiException(self::UNEXPECTED_ERROR . $httpResponseCode);
                        break;
                }

            case 401:
                $json = json_decode($response);
                curl_close($ch);


                $ehiveStatusMessage = new Exception\StatusMessage($json);

                throw new Exception\UnauthorizedException($ehiveStatusMessage->toString());

            case 403:
                $json = json_decode($response);
                curl_close($ch);


                $ehiveStatusMessage = new Exception\StatusMessage($json);

                throw new Exception\ForbiddenException($ehiveStatusMessage->toString());

            case 404:
                curl_close($ch);
                throw new Exception\NotFoundException(self::RESOURCE_NOT_FOUND);
                break;

            case 500:
                $json = json_decode($response);
                curl_close($ch);


                $ehiveStatusMessage = new Exception\StatusMessage($json);

                throw new Exception\FatalServerException($ehiveStatusMessage->toString());
                break;

            case 503:
                throw new Exception\ApiException(self::EHIVE_DOWN);
                break;

            default:
                throw new Exception\ApiException(self::UNEXPECTED_ERROR . $httpResponseCode);
                break;
        }
    }

    private function getOauthCredentials()
    {
        $oauthCredentials = new OauthCredentials();

        $oauthCredentials->clientId = $this->clientId;
        $oauthCredentials->clientSecret = $this->clientSecret;
        $oauthCredentials->oauthToken = $this->oauthToken;

        return $oauthCredentials;
    }

    private function createUrl($uri, $queryString = '')
    {
        if (empty($queryString)) {
            $uri .= '?trackingId=' . $this->trackingId;
        } else {
            $uri .= '?' . $queryString . '&trackingId=' . $this->trackingId;
        }

        return self::HTTPS_PROTOCOL . $uri;
    }

    private function asOauthCredentials($json)
    {
        $oauthCredentials = new OauthCredentials();

        $oauthCredentials->clientId = isset($json->clientId) ? $json->clientId : null;
        $oauthCredentials->clientSecret = isset($json->clientSecret) ? $json->clientSecret : null;
        $oauthCredentials->oauthToken = isset($json->oauthToken) ? $json->oauthToken : null;

        return $oauthCredentials;
    }

    private function memcacheKey($path, $queryString)
    {
        return md5($path . $queryString);
    }
}
