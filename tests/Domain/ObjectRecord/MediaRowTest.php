<?php

namespace EHive\Tests\Domain\ObjectRecord;

use EHive\Domain\ObjectRecord\Media;
use EHive\Domain\ObjectRecord\MediaRow;

class MediaRowTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new MediaRow(o([
            'media' => [
                [
                    'identifier' => 'id1'
                ],
                [
                    'identifier' => 'id2'
                ],
            ]
        ]));

        $this->assertInternalType('array', $obj->media);
        $this->assertNotEmpty($obj->media);
        $this->assertArrayHasKey('id1', $obj->media);
        $this->assertArrayHasKey('id2', $obj->media);
        $this->assertInstanceOf(Media::class, $obj->media['id1']);
        $this->assertInstanceOf(Media::class, $obj->media['id2']);
    }

    public function testGetFieldByIdentifier()
    {
        $obj = new MediaRow(o([
            'media' => [
                [
                    'identifier' => 'id1',
                    'attributes' => [
                        [
                            'key' => 'a1',
                            'value' => 'val'
                        ]
                    ]
                ],
            ]
        ]));

        $this->assertInstanceOf(Media::class, $obj->getMediaByIdentifier('id1'));
        $this->assertNull($obj->getMediaByIdentifier('bar'));
    }
}
