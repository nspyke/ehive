<?php
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
