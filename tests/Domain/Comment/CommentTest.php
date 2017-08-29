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

class CommentTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new Comment(o([
            'commentId' => 'commentId',
            'commentorName' => 'commentorName',
            'commentorEmailAddress' => 'commentorEmailAddress',
            'commentText' => 'commentText',
            'whenCreated' => 'whenCreated',
        ]));

        $this->assertEquals('commentId', $obj->commentId);
        $this->assertEquals('commentorName', $obj->commentorName);
        $this->assertEquals('commentorEmailAddress', $obj->commentorEmailAddress);
        $this->assertEquals('commentText', $obj->commentText);
        $this->assertEquals('whenCreated', $obj->whenCreated);
    }
}
