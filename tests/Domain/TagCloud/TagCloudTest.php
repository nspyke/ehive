<?php
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
