<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EHive\Tests\Domain\Comment;

use EHive\Domain\Comment\Comment;
use EHive\Domain\Comment\CommentsCollection;

class CommentsCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new CommentsCollection(o([
            'objectRecordId' => 'objectRecordId',
            'allowCommenting' => true,
            'comments' => [
                ['commentId' => 'commentId']
            ]
        ]));

        $this->assertEquals('objectRecordId', $obj->objectRecordId);
        $this->assertTrue($obj->allowCommenting);
        $this->assertInternalType('array', $obj->comments);
        $this->assertNotEmpty($obj->comments);
        $this->assertInstanceOf(Comment::class, $obj->comments[0]);
        $this->assertEquals('commentId', $obj->comments[0]->commentId);
    }
}
