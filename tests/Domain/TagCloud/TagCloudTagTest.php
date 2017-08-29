<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EHive\Tests\Domain;

use EHive\Domain\TagCloud\TagCloudTag;

class TagCloudTagTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new TagCloudTag(o([
            'percentage' => 1,
            'cleanTagName' => 'abc'
        ]));

        $this->assertEquals(1, $obj->percentage);
        $this->assertEquals('abc', $obj->cleanTagName);
    }
}
