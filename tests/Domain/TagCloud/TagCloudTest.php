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

use EHive\Domain\TagCloud\TagCloud;
use EHive\Domain\TagCloud\TagCloudTag;

class TagCloudTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new TagCloud(o([
            'tagCloudTags' => [
                [
                    'percentage' => 1,
                    'cleanTagName' => 'abc'
                ]
            ],
        ]));

        $this->assertInternalType('array', $obj->tagCloudTags);
        $this->assertNotEmpty($obj->tagCloudTags);
        $this->assertInstanceOf(TagCloudTag::class, $obj->tagCloudTags[0]);
    }
}
