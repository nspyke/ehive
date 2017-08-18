<?php

namespace EHive\Tests\Domain\Community;

use EHive\Domain\Community\Community;
use EHive\Domain\ObjectRecord\MediaSet;

class CommunityTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new Community(o([
            'communityId' => 'communityId',
            'name' => 'name',
            'description' => 'description',
            'searchScore' => 'searchScore',
        ]));

        $this->assertEquals('communityId', $obj->communityId);
        $this->assertEquals('name', $obj->name);
        $this->assertEquals('description', $obj->description);
        $this->assertEquals('searchScore', $obj->searchScore);
    }

    public function testGetMediaSetByIdentifier()
    {
        $obj = new Community(o([
            'mediaSets' => [
                [
                    'identifier' => 'foo',
                    'mediaRows' => [],
                ],
            ],
        ]));

        $this->assertInstanceOf(MediaSet::class, $obj->getMediaSetByIdentifier('foo'));
        $this->assertNull($obj->getMediaSetByIdentifier('bar'));
    }
}
