<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EHive\Tests;

use EHive\Domain;
use EHive\ApiClient;
use EHive\Transport\OauthCredentials;
use EHive\Transport\Transport;

class FunctionalApiClientTest extends \PHPUnit_Framework_TestCase
{
    private static $token;

    private $transport;
    private $accountId;
    private $objectId;
    private $communityId;

    public function testGetAccount()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getAccount($this->accountId);
        $this->assertInstanceOf(Domain\Account\Account::class, $obj);
    }

    public function testGetAccountInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getAccountInCommunity($this->communityId, $this->accountId);
        $this->assertInstanceOf(Domain\Account\Account::class, $obj);
    }

    public function testGetAccountsInEHive()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getAccountsInEHive('red');
        $this->assertInstanceOf(Domain\Account\AccountsCollection::class, $obj);
    }

    public function testGetAccountsInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getAccountsInCommunity($this->communityId, 'red');
        $this->assertInstanceOf(Domain\Account\AccountsCollection::class, $obj);
    }

    public function testGetCommunitiesModeratoredByAccount()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getCommunitiesModeratoredByAccount($this->accountId);
        $this->assertInstanceOf(Domain\Community\CommunitiesCollection::class, $obj);
    }

    public function testGetCommunitiesInEHive()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getCommunitiesInEHive('red');
        $this->assertInstanceOf(Domain\Community\CommunitiesCollection::class, $obj);
    }

    public function testGetObjectRecord()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getObjectRecord($this->objectId);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecord::class, $obj);
    }

    public function testGetObjectRecordsInEHive()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getObjectRecordsInEHive('red', true);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetObjectRecordsInAccount()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getObjectRecordsInAccount($this->accountId, 'red', true);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetObjectRecordsInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getObjectRecordsInCommunity($this->communityId, 'red', true);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetObjectRecordsInAccountInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getObjectRecordsInAccountInCommunity($this->communityId, $this->accountId, 'red', true);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetInterestingObjectRecordsInEHive()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getInterestingObjectRecordsInEHive(true, 'art');
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetInterestingObjectRecordsInAccount()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getInterestingObjectRecordsInAccount($this->accountId, 'art');
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetInterestingObjectRecordsInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getInterestingObjectRecordsInCommunity($this->communityId, 'art');
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetInterestingObjectRecordsInAccountInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getInterestingObjectRecordsInAccountInCommunity($this->communityId, $this->accountId);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetPopularObjectRecordsInEHive()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getPopularObjectRecordsInEHive('art');
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetPopularObjectRecordsInAccount()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getPopularObjectRecordsInAccount($this->accountId);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetPopularObjectRecordsInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getPopularObjectRecordsInCommunity($this->communityId);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetPopularObjectRecordsInAccountInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getPopularObjectRecordsInAccountInCommunity($this->communityId, $this->accountId);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetRecentObjectRecordsInEHive()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getRecentObjectRecordsInEHive('art');
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetRecentObjectRecordsInAccount()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getRecentObjectRecordsInAccount($this->accountId);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetRecentObjectRecordsInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getRecentObjectRecordsInCommunity($this->communityId);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetRecentObjectRecordsInAccountInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getRecentObjectRecordsInAccountInCommunity($this->communityId, $this->accountId);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetObjectRecordComments()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getObjectRecordComments($this->objectId);
        $this->assertInstanceOf(Domain\Comment\CommentsCollection::class, $obj);
    }

    public function testGetTagCloudInEHive()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getTagCloudInEHive(5);
        $this->assertInstanceOf(Domain\TagCloud\TagCloud::class, $obj);
    }

    public function testGetTagCloudInAccount()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getTagCloudInAccount($this->accountId, 1);
        $this->assertInstanceOf(Domain\TagCloud\TagCloud::class, $obj);
    }

    public function testGetTagCloudInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getTagCloudInCommunity($this->communityId, 1);
        $this->assertInstanceOf(Domain\TagCloud\TagCloud::class, $obj);
    }

    protected function setUp()
    {
        $clientId = getenv('CLIENT_ID');
        $clientSecret = getenv('CLIENT_SECRET');
        $trackingId = getenv('TRACKING_ID');

        $this->accountId = getenv('ACCOUNT_ID');
        $this->communityId = getenv('COMMUNITY_ID');
        $this->objectId = getenv('OBJECT_ID');

        if (empty($clientId) or
            empty($clientSecret) or
            empty($trackingId) or
            empty($this->accountId) or
            empty($this->communityId) or
            empty($this->objectId)
        ) {
            $this->markTestSkipped('All of the environment variables have not been set. Can not continue.');
        }

        $this->transport = new Transport(
            $clientId,
            $clientSecret,
            $trackingId,
            self::$token,
            function (OauthCredentials $creds) {
                self::$token = $creds->oauthToken;
            }
        );
    }
}
