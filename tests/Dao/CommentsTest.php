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

use EHive\Dao\Comments;
use EHive\Domain\Comment\Comment;
use EHive\Domain\Comment\CommentsCollection;

class CommentsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $transport;

    public function testAddObjectRecordComment()
    {
        $this->transport->expects($this->once())
            ->method('post')
            ->willReturn(json_decode(json_encode(['commentId' => 123])));

        $dao = new Comments($this->transport);

        $response = $dao->addObjectRecordComment(123, 'foobar');
        $this->assertInstanceOf(Comment::class, $response);
        $this->assertEquals(123, $response->commentId);
    }

    public function testGetObjectRecordComments()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode(['objectRecordId' => 123])));

        $dao = new Comments($this->transport);

        $response = $dao->getObjectRecordComments(123);
        $this->assertInstanceOf(CommentsCollection::class, $response);
        $this->assertEquals(123, $response->objectRecordId);
    }

    protected function setUp()
    {
        $this->transport = $this->getMockBuilder(\EHive\Transport\Transport::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
