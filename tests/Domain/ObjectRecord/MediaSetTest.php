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

use EHive\Domain\ObjectRecord\MediaRow;
use EHive\Domain\ObjectRecord\MediaSet;

class MediaSetTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new MediaSet(o([
            'identifier' => 'id',
            'mediaRows' => [
                []
            ]
        ]));

        $this->assertEquals('id', $obj->identifier);
        $this->assertInternalType('array', $obj->mediaRows);
        $this->assertNotEmpty($obj->mediaRows);
        $this->assertInstanceOf(MediaRow::class, $obj->mediaRows[0]);
    }
}
