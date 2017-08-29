<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EHive\Tests\Transport;

use EHive\Exception\ApiException;
use EHive\Transport\OauthCredentials;
use EHive\Transport\Transport;
use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;

class TransportTest extends \PHPUnit_Framework_TestCase
{
    private $token;

    use HttpMockTrait;

    public static function setUpBeforeClass()
    {
        static::setUpHttpMockBeforeClass(28080);
    }

    public static function tearDownAfterClass()
    {
        static::tearDownHttpMockAfterClass();
    }

    public function setUp()
    {
        $this->setUpHttpMock();
    }

    public function tearDown()
    {
        $this->tearDownHttpMock();
    }

    public function testGet()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/api/foo?q=red&trackingId=tracking_id')

            ->then()
                ->body(json_encode(['foo' => 'bar']))
                ->statusCode(200)
            ->end();
        $this->http->setUp();

        $cache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $cache->expects($this->once())
            ->method('has')
            ->willReturn(false);
        $cache->expects($this->once())
            ->method('set')
            ->with($this->anything(), o(['foo' => 'bar']), 7200);

        $transport = new Transport('client_id', 'client_secret', 'tracking_id');
        $transport->setCache($cache);
        $transport->setCacheTtl(7200);
        $transport->setApiUrl('http://localhost:28080');

        $response = $transport->get('/api/foo', 'q=red');
        $expected = new \stdClass();
        $expected->foo = 'bar';
        $this->assertEquals($expected, $response);

        $request = $this->http->requests->latest();
        $this->assertSame(
            'application/json',
            (string) $request->getHeader('Content-Type'),
            'Client should send application/json'
        );
        $this->assertEquals(1, $this->http->requests->count());
    }

    public function testCachedGet()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/api/foo')
            ->then()
                ->body(json_encode(['foo' => 'bar']))
                ->statusCode(200)
            ->end();
        $this->http->setUp();

        $cache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $cache->expects($this->once())
            ->method('has')
            ->willReturn(true);
        $cache->expects($this->once())
            ->method('get')
            ->willReturn(o(['foo' => 'bar']));

        $transport = new Transport('foo', 'bar');
        $transport->setCache($cache);
        $transport->setApiUrl('http://localhost:28080');

        $response = $transport->get('/api/foo');
        $expected = new \stdClass();
        $expected->foo = 'bar';
        $this->assertEquals($expected, $response);
        $this->assertEquals(0, $this->http->requests->count());
    }

    public function testAuthenticatedGet()
    {
        $this->setupAuth();
        $this->http->mock
            ->when()
                ->callback(static function (Request $request) {
                    return $request->headers->get('Authorization') === 'Basic ';
                })
            ->then()
                ->body('')
                ->statusCode(401)
            ->end()
            ->when()
                ->callback(static function (Request $request) {
                    return $request->headers->get('Authorization') === 'Basic token';
                })
            ->then()
                ->body(json_encode(['foo' => 'bar']))
                ->statusCode(200)
            ->end()
        ;
        $this->http->setUp();

        $transport = new Transport('client_id', 'client_secret');
        $transport->setApiUrl('http://localhost:28080');
        $transport->setOauthTokenCallback(function (OauthCredentials $credentials) {
            $this->token = $credentials;
        });
        $response = $transport->get('/api/foo');

        $this->assertEquals(4, $this->http->requests->count());
        $expected = new \stdClass();
        $expected->foo = 'bar';
        $this->assertEquals($expected, $response);
        $this->assertEquals('token', $this->token->oauthToken);
    }

    /**
     * @expectedException \EHive\Exception\BadRequestException
     */
    public function testGetStatus400()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/bad-request')
            ->then()
                ->body(json_encode([
                    'requestMessage' => 'bad',
                    'requestFields' => [
                        ['fieldName' => 'a', 'fieldMessage' => 'msg'],
                    ],
                ]))
                ->statusCode(400)
            ->end();
        $this->http->setUp();
        $transport = new Transport('client_id', 'client_secret');
        $transport->setApiUrl('http://localhost:28080');
        $transport->get('/bad-request');
    }

    /**
     * @expectedException \EHive\Exception\BadRequestException
     * @expectedExceptionMessage Bad Request
     */
    public function testGetStatus400NoMessage()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/bad-request')
            ->then()
                ->statusCode(400)
            ->end();
        $this->http->setUp();
        $transport = new Transport('client_id', 'client_secret');
        $transport->setApiUrl('http://localhost:28080');
        $transport->get('/bad-request');
    }

    /**
     * @expectedException \EHive\Exception\ForbiddenException
     */
    public function testGetStatus403()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/forbidden')
            ->then()
                ->statusCode(403)
            ->end();
        $this->http->setUp();
        $transport = new Transport('client_id', 'client_secret');
        $transport->setApiUrl('http://localhost:28080');
        $transport->get('/forbidden');
    }

    /**
     * @expectedException \EHive\Exception\NotFoundException
     */
    public function testGetStatus404()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/not-found')
            ->then()
                ->statusCode(404)
            ->end();
        $this->http->setUp();
        $transport = new Transport('client_id', 'client_secret');
        $transport->setApiUrl('http://localhost:28080');
        $transport->get('/not-found');
    }

    /**
     * @expectedException \EHive\Exception\FatalServerException
     */
    public function testGetStatus500()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/server-error')
            ->then()
                ->statusCode(500)
            ->end();
        $this->http->setUp();
        $transport = new Transport('client_id', 'client_secret');
        $transport->setApiUrl('http://localhost:28080');
        $transport->get('/server-error');
    }

    /**
     * @expectedException \EHive\Exception\ApiException
     * @expectedExceptionMessage eHive is currently down for a short period of maintenance. HTTP response code: 503
     */
    public function testGetStatus503()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/server-unavailable')
            ->then()
                ->statusCode(503)
            ->end();
        $this->http->setUp();
        $transport = new Transport('client_id', 'client_secret');
        $transport->setApiUrl('http://localhost:28080');
        $transport->get('/server-unavailable');
    }

    /**
     * @expectedException \EHive\Exception\ApiException
     */
    public function testCurlErrorOnGet()
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects($this->once())
            ->method('error');

        $transport = new Transport();
        $transport->setLogger($logger);
        $transport->setApiUrl('http://foo');

        $transport->get('/bar');
    }

    public function testPost()
    {
        $this->http->mock
            ->when()
                ->methodIs('POST')
                ->pathIs('/api/foo?trackingId=track')
            ->then()
                ->body(json_encode(['foo' => 'bar']))
                ->statusCode(200)
            ->end();
        $this->http->setUp();

        $cache = $this->getMockBuilder(CacheInterface::class)->getMock();

        $transport = new Transport('client_id', 'client_secret', 'track');
        $transport->setOauthToken('token');
        $transport->setCache($cache);
        $transport->setApiUrl('http://localhost:28080');

        $response = $transport->post('/api/foo');
        $expected = new \stdClass();
        $expected->foo = 'bar';
        $this->assertEquals($expected, $response);

        $request = $this->http->requests->latest();
        $this->assertEquals(1, $this->http->requests->count());
    }


    public function testAuthenticatedPost()
    {
        $this->setupAuth();
        $this->http->mock
            ->when()
                ->callback(static function (Request $request) {
                    return $request->headers->get('Authorization') === 'Basic ';
                })
            ->then()
                ->body('')
                ->statusCode(401)
            ->end()
            ->when()
                ->callback(static function (Request $request) {
                    return $request->headers->get('Authorization') === 'Basic token';
                })
            ->then()
                ->body(json_encode(['foo' => 'bar']))
                ->statusCode(200)
            ->end()
        ;
        $this->http->setUp();

        $transport = new Transport('client_id', 'client_secret');
        $transport->setApiUrl('http://localhost:28080');
        $transport->setOauthTokenCallback(function (OauthCredentials $credentials) {
            $this->token = $credentials;
        });
        $response = $transport->post('/api/foo');

        $this->assertEquals(4, $this->http->requests->count());
        $expected = new \stdClass();
        $expected->foo = 'bar';
        $this->assertEquals($expected, $response);
        $this->assertEquals('token', $this->token->oauthToken);
    }

    public function testPut()
    {
        $this->http->mock
            ->when()
                ->methodIs('PUT')
                ->pathIs('/api/foo')
            ->then()
                ->body(json_encode(['foo' => 'bar']))
                ->statusCode(200)
            ->end();
        $this->http->setUp();

        $cache = $this->getMockBuilder(CacheInterface::class)->getMock();

        $transport = new Transport('client_id', 'client_secret');
        $transport->setOauthToken('token');
        $transport->setCache($cache);
        $transport->setApiUrl('http://localhost:28080');

        $response = $transport->put('/api/foo');
        $expected = new \stdClass();
        $expected->foo = 'bar';
        $this->assertEquals($expected, $response);

        $request = $this->http->requests->latest();
        $this->assertEquals(1, $this->http->requests->count());
    }


    public function testAuthenticatedPut()
    {
        $this->setupAuth();
        $this->http->mock
            ->when()
                ->callback(static function (Request $request) {
                    return $request->headers->get('Authorization') === 'Basic ';
                })
            ->then()
                ->body('')
                ->statusCode(401)
            ->end()
            ->when()
                ->callback(static function (Request $request) {
                    return $request->headers->get('Authorization') === 'Basic token';
                })
            ->then()
                ->body(json_encode(['foo' => 'bar']))
                ->statusCode(200)
            ->end()
        ;
        $this->http->setUp();

        $transport = new Transport('client_id', 'client_secret');
        $transport->setApiUrl('http://localhost:28080');
        $transport->setOauthTokenCallback(function (OauthCredentials $credentials) {
            $this->token = $credentials;
        });
        $response = $transport->put('/api/foo');

        $this->assertEquals(4, $this->http->requests->count());
        $expected = new \stdClass();
        $expected->foo = 'bar';
        $this->assertEquals($expected, $response);
        $this->assertEquals('token', $this->token->oauthToken);
    }

    public function testDelete()
    {
        $this->http->mock
            ->when()
                ->methodIs('DELETE')
                ->pathIs('/api/foo')
            ->then()
                ->body(json_encode(['foo' => 'bar']))
                ->statusCode(200)
            ->end();
        $this->http->setUp();

        $cache = $this->getMockBuilder(CacheInterface::class)->getMock();

        $transport = new Transport('client_id', 'client_secret');
        $transport->setOauthToken('token');
        $transport->setCache($cache);
        $transport->setApiUrl('http://localhost:28080');

        $response = $transport->delete('/api/foo');
        $expected = new \stdClass();
        $expected->foo = 'bar';
        $this->assertEquals($expected, $response);

        $this->assertEquals(1, $this->http->requests->count());
    }


    public function testAuthenticatedDelete()
    {
        $this->setupAuth();
        $this->http->mock
            ->when()
                ->callback(static function (Request $request) {
                    return $request->headers->get('Authorization') === 'Basic ';
                })
            ->then()
                ->body('')
                ->statusCode(401)
            ->end()
            ->when()
                ->callback(static function (Request $request) {
                    return $request->headers->get('Authorization') === 'Basic token';
                })
            ->then()
                ->body(json_encode(['foo' => 'bar']))
                ->statusCode(200)
            ->end()
        ;
        $this->http->setUp();

        $transport = new Transport('client_id', 'client_secret');
        $transport->setApiUrl('http://localhost:28080');
        $transport->setOauthTokenCallback(function (OauthCredentials $credentials) {
            $this->token = $credentials;
        });
        $response = $transport->delete('/api/foo');

        $this->assertEquals(4, $this->http->requests->count());
        $expected = new \stdClass();
        $expected->foo = 'bar';
        $this->assertEquals($expected, $response);
        $this->assertEquals('token', $this->token->oauthToken);
    }

    private function setupAuth()
    {
        $this->http->mock
            ->when()
                ->methodIs('POST')
                ->pathIs('/oauth2/v2/authorize')
            ->then()
                ->body('')
                ->header('Access-Grant', 'access_grant')
                ->header('Authorization', 'authorization')
                ->header('Client-Id', 'client_id')
                ->header('Grant-Type', 'client_credentials')
                ->statusCode(303)
            ->end()
            ->when()
                ->methodIs('GET')
                ->pathIs('/oauth2/v2/token')
            ->then()
                ->body(json_encode(new OauthCredentials(
                    'client_id',
                    'client_secret',
                    'token'
                )))
                ->statusCode(200)
            ->end();
    }
}
