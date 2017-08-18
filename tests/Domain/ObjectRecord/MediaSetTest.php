<?php

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
