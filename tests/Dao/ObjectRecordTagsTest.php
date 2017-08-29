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

use EHive\Dao\ObjectRecordTags;
use EHive\Domain\ObjectRecordTag\ObjectRecordTag;
use EHive\Domain\ObjectRecordTag\ObjectRecordTagsCollection;

class ObjectRecordTagsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $transport;

    public function testGetObjectRecordTags()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['objectRecordId' => 123])));

        $dao = new ObjectRecordTags($this->transport);

        $response = $dao->getObjectRecordTags(123);
        $this->assertInstanceOf(ObjectRecordTagsCollection::class, $response);
        $this->assertEquals(123, $response->objectRecordId);
    }

    public function testAddObjectRecordTag()
    {
        $this->transport->expects($this->once())
            ->method('post')
            ->willReturn(json_decode(json_encode(['cleanTagName' => 'abc'])));

        $dao = new ObjectRecordTags($this->transport);

        $response = $dao->addObjectRecordTag(123, 'abc');
        $this->assertInstanceOf(ObjectRecordTag::class, $response);
        $this->assertEquals('abc', $response->cleanTagName);
    }

    public function testDeleteObjectRecordTag()
    {
        $this->transport->expects($this->once())
            ->method('delete')
            ->willReturn(json_decode(json_encode(['cleanTagName' => 'abc'])));

        $dao = new ObjectRecordTags($this->transport);

        $tag = new ObjectRecordTag();
        $tag->rawTagName = 'abc';
        $response = $dao->deleteObjectRecordTag(123, $tag);
        $this->assertInstanceOf(ObjectRecordTag::class, $response);
        $this->assertEquals('abc', $response->cleanTagName);
    }

    protected function setUp()
    {
        $this->transport = $this->getMockBuilder(\EHive\Transport\Transport::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
