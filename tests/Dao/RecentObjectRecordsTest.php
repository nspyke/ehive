<?php
namespace EHive\Tests\Dao;

use EHive\Dao\RecentObjectRecords;
use EHive\Domain\ObjectRecord\ObjectRecordsCollection;

class RecentObjectRecordsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $transport;

    public function testGetRecentObjectRecordsInEHive()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 123])));

        $dao = new RecentObjectRecords($this->transport);

        $response = $dao->getRecentObjectRecordsInEHive('art', true, 1, 1);
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(123, $response->totalObjects);
    }

    public function testGetRecentObjectRecordsInAccount()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 123])));

        $dao = new RecentObjectRecords($this->transport);

        $response = $dao->getRecentObjectRecordsInAccount(123, 'art', true, 1, 1, 'def');
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(123, $response->totalObjects);
    }

    public function testGetRecentObjectRecordsInCommunity()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 123])));

        $dao = new RecentObjectRecords($this->transport);

        $response = $dao->getRecentObjectRecordsInCommunity(123, 'art', true, 1, 1);
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(123, $response->totalObjects);
    }

    public function testGetRecentObjectRecordsInAccountInCommunity()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 123])));

        $dao = new RecentObjectRecords($this->transport);

        $response = $dao->getRecentObjectRecordsInAccountInCommunity(123, 234, 'art', true, 1, 1);
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
