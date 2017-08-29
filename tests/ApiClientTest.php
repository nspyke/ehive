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

use EHive\ApiClient;
use EHive\Domain;
use EHive\Transport\Transport;

class ApiClientTest extends \PHPUnit_Framework_TestCase
{
    private $transport;

    public function testGetAccount()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getAccount(123);
        $this->assertInstanceOf(Domain\Account\Account::class, $obj);
    }

    public function testGetAccountInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getAccountInCommunity(123, 123);
        $this->assertInstanceOf(Domain\Account\Account::class, $obj);
    }

    public function testGetAccountsInEHive()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getAccountsInEHive(123, 123, 'a');
        $this->assertInstanceOf(Domain\Account\AccountsCollection::class, $obj);
    }

    public function testGetAccountsInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getAccountsInCommunity(123, 123, 'a', 'a');
        $this->assertInstanceOf(Domain\Account\AccountsCollection::class, $obj);
    }

    public function testGetCommunitiesModeratoredByAccount()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getCommunitiesModeratoredByAccount(123);
        $this->assertInstanceOf(Domain\Community\CommunitiesCollection::class, $obj);
    }

    public function testGetCommunitiesInEHive()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getCommunitiesInEHive('ac', 'a', 'd');
        $this->assertInstanceOf(Domain\Community\CommunitiesCollection::class, $obj);
    }

    public function testGetObjectRecord()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getObjectRecord(123);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecord::class, $obj);
    }

    public function testGetObjectRecordsInEHive()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getObjectRecordsInEHive('ac', true, 'a', 'd');
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetObjectRecordsInAccount()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getObjectRecordsInAccount(222, 'ac', true, 'a', 'd');
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetObjectRecordsInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getObjectRecordsInCommunity(222, 'ac', true, 'a', 'd');
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetObjectRecordsInAccountInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getObjectRecordsInAccountInCommunity(123, 222, 'ac', true, 'a', 'd');
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
        $obj = $client->getInterestingObjectRecordsInAccount(123, 'art');
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetInterestingObjectRecordsInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getInterestingObjectRecordsInCommunity(123, 'art');
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetInterestingObjectRecordsInAccountInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getInterestingObjectRecordsInAccountInCommunity(123, 'art');
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
        $obj = $client->getPopularObjectRecordsInAccount(123);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetPopularObjectRecordsInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getPopularObjectRecordsInCommunity(123);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetPopularObjectRecordsInAccountInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getPopularObjectRecordsInAccountInCommunity(123, 123);
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
        $obj = $client->getRecentObjectRecordsInAccount(123);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetRecentObjectRecordsInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getRecentObjectRecordsInCommunity(123);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetRecentObjectRecordsInAccountInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getRecentObjectRecordsInAccountInCommunity(123, 34);
        $this->assertInstanceOf(Domain\ObjectRecord\ObjectRecordsCollection::class, $obj);
    }

    public function testGetObjectRecordComments()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getObjectRecordComments(123, 0);
        $this->assertInstanceOf(Domain\Comment\CommentsCollection::class, $obj);
    }

    public function testAddObjectRecordComment()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->addObjectRecordComment(123, 'acs');
        $this->assertInstanceOf(Domain\Comment\Comment::class, $obj);
    }

    public function testGetObjectRecordTags()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getObjectRecordTags(123);
        $this->assertInstanceOf(Domain\ObjectRecordTag\ObjectRecordTagsCollection::class, $obj);
    }

    public function testAddObjectRecordTag()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->addObjectRecordTag(123, 'acs');
        $this->assertInstanceOf(Domain\ObjectRecordTag\ObjectRecordTag::class, $obj);
    }

    public function testDeleteObjectRecordTag()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->deleteObjectRecordTag(123, new Domain\ObjectRecordTag\ObjectRecordTag());
        $this->assertInstanceOf(Domain\ObjectRecordTag\ObjectRecordTag::class, $obj);
    }

    public function testGetTagCloudInEHive()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getTagCloudInEHive(12);
        $this->assertInstanceOf(Domain\TagCloud\TagCloud::class, $obj);
    }

    public function testGetTagCloudInAccount()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getTagCloudInAccount(12, 1);
        $this->assertInstanceOf(Domain\TagCloud\TagCloud::class, $obj);
    }

    public function testGetTagCloudInCommunity()
    {
        $client = new ApiClient($this->transport);
        $obj = $client->getTagCloudInCommunity(12, 1);
        $this->assertInstanceOf(Domain\TagCloud\TagCloud::class, $obj);
    }

    protected function setUp()
    {
        $this->transport = $this->getMockBuilder(Transport::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
