<?php

namespace EHive\Tests\Domain\ObjectRecord;

use EHive\Domain\ObjectRecord\FieldRow;
use EHive\Domain\ObjectRecord\FieldSet;

class FieldSetTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new FieldSet(o([
            'identifier' => 'id',
            'fieldRows' => [
                []
            ]
        ]));

        $this->assertEquals('id', $obj->identifier);
        $this->assertInternalType('array', $obj->fieldRows);
        $this->assertNotEmpty($obj->fieldRows);
        $this->assertInstanceOf(FieldRow::class, $obj->fieldRows[0]);
    }
}
