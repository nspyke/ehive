<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EHive\Tests\Dao;

use EHive\Dao\InterestingObjectRecords;
use EHive\Domain\ObjectRecord\ObjectRecordsCollection;

class InterestingObjectRecordsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $transport;

    public function testGetInterestingObjectRecordsInEHive()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 1])));

        $dao = new InterestingObjectRecords($this->transport);

        $response = $dao->getInterestingObjectRecordsInEHive(true, '', 1, 1);
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(1, $response->totalObjects);
    }

    public function testGetInterestingObjectRecordsInAccount()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 1])));

        $dao = new InterestingObjectRecords($this->transport);

        $response = $dao->getInterestingObjectRecordsInAccount(123, '', true, 1, 1, 'abc');
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(1, $response->totalObjects);
    }

    public function testGetInterestingObjectRecordsInCommunity()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 1])));

        $dao = new InterestingObjectRecords($this->transport);

        $response = $dao->getInterestingObjectRecordsInCommunity(123, '', true, 1, 1);
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(1, $response->totalObjects);
    }

    public function testGetInterestingObjectRecordsInAccountInCommunity()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 1])));

        $dao = new InterestingObjectRecords($this->transport);

        $response = $dao->getInterestingObjectRecordsInAccountInCommunity(123, 123, '', true, 1, 1);
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(1, $response->totalObjects);
    }

    protected function setUp()
    {
        $this->transport = $this->getMockBuilder(\EHive\Transport\Transport::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
