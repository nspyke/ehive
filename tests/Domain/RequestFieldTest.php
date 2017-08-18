<?php

namespace EHive\Tests\Domain;

use EHive\Domain\RequestField;

class RequestFieldTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new RequestField(o([
            'fieldName' => 'a',
            'fieldMessage' => 'b',
        ]));
        $this->assertEquals('a', $obj->fieldName);
        $this->assertEquals('b', $obj->fieldMessage);
    }
}
