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

use EHive\Dao\ObjectRecords;
use EHive\Domain\ObjectRecord\ObjectRecord;
use EHive\Domain\ObjectRecord\ObjectRecordsCollection;

class ObjectRecordsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $transport;

    public function testGetObjectRecord()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['objectRecordId' => 123])));

        $dao = new ObjectRecords($this->transport);

        $response = $dao->getObjectRecord(123);
        $this->assertInstanceOf(ObjectRecord::class, $response);
        $this->assertEquals(123, $response->objectRecordId);
    }

    public function testGetObjectRecordsInEHive()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 123])));

        $dao = new ObjectRecords($this->transport);

        $response = $dao->getObjectRecordsInEHive('abc', true, 'a', 'a', 1, 1);
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(123, $response->totalObjects);
    }

    public function testGetObjectRecordsInAccount()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 123])));

        $dao = new ObjectRecords($this->transport);

        $response = $dao->getObjectRecordsInAccount(123, 'abc', true, 'a', 'a', 1, 1, 'def');
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(123, $response->totalObjects);
    }

    public function testGetObjectRecordsInCommunity()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 123])));

        $dao = new ObjectRecords($this->transport);

        $response = $dao->getObjectRecordsInCommunity(123, 'abc', true, 'a', 'a', 1, 1);
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(123, $response->totalObjects);
    }

    public function testGetObjectRecordsInAccountInCommunity()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 123])));

        $dao = new ObjectRecords($this->transport);

        $response = $dao->getObjectRecordsInAccountInCommunity(123, 234, 'abc', true, 'a', 'a', 1, 1);
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(123, $response->totalObjects);
    }

    protected function setUp()
    {
        $this->transport = $this->getMockBuilder(\EHive\Transport\Transport::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
