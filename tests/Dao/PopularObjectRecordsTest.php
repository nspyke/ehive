<?php
namespace EHive\Tests\Dao;

use EHive\Dao\PopularObjectRecords;
use EHive\Domain\ObjectRecord\ObjectRecordsCollection;

class PopularObjectRecordsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $transport;

    public function testGetPopularObjectRecordsInEHive()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 123])));

        $dao = new PopularObjectRecords($this->transport);

        $response = $dao->getPopularObjectRecordsInEHive('art', true, 1, 1);
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(123, $response->totalObjects);
    }

    public function testGetPopularObjectRecordsInAccount()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 123])));

        $dao = new PopularObjectRecords($this->transport);

        $response = $dao->getPopularObjectRecordsInAccount(123, 'art', true, 1, 1, 'abc');
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(123, $response->totalObjects);
    }

    public function testGetPopularObjectRecordsInCommunity()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 123])));

        $dao = new PopularObjectRecords($this->transport);

        $response = $dao->getPopularObjectRecordsInCommunity(123, 'art', true, 1, 1);
        $this->assertInstanceOf(ObjectRecordsCollection::class, $response);
        $this->assertEquals(123, $response->totalObjects);
    }

    public function testGetPopularObjectRecordsInAccountInCommunity()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['totalObjects' => 123])));

        $dao = new PopularObjectRecords($this->transport);

        $response = $dao->getPopularObjectRecordsInAccountInCommunity(123, 123, 'art', true, 1, 1);
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
