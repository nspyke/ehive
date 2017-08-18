<?php

namespace EHive\Tests\Domain\ObjectRecord;

use EHive\Domain\ObjectRecord\Attribute;

class AttributeTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new Attribute(o([
            'key' => 'key',
            'value' => 'value'
        ]));

        $this->assertEquals('key', $obj->key);
        $this->assertEquals('value', $obj->value);
    }
}
