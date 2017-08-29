<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EHive\Tests\Domain\ObjectRecord;

use EHive\Domain\ObjectRecord\Attribute;
use EHive\Domain\ObjectRecord\Media;

class MediaTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new Media(o([
            'identifier' => 'id1',
            'attributes' => [
                [
                    'key' => 'k1',
                    'value' => 'v1',
                ],
            ]
        ]));

        $this->assertInternalType('array', $obj->attributes);
        $this->assertNotEmpty($obj->attributes);
        $this->assertArrayHasKey('k1', $obj->attributes);
        $this->assertInstanceOf(Attribute::class, $obj->attributes['k1']);
    }

    public function testGetMediaAttribute()
    {
        $obj = new Media(o([
            'identifier' => 'id1',
            'attributes' => [
                [
                    'key' => 'k1',
                    'value' => 'v1',
                ],
            ]
        ]));

        $this->assertEquals('v1', $obj->getMediaAttribute('k1'));
        $this->assertNull($obj->getMediaAttribute('foo'));
    }
}
