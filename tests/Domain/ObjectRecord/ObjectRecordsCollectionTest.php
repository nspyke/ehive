<?php

namespace EHive\Tests\Domain\ObjectRecord;

use EHive\Domain\ObjectRecord\ObjectRecord;
use EHive\Domain\ObjectRecord\ObjectRecordsCollection;

class ObjectRecordsCollectionTest extends \PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $obj = new ObjectRecordsCollection(o([
            'totalObjects' => 2,
            'maxSearchScore' => 100,
            'objectRecords' => [
                [
                    'objectRecordId' => 123
                ]
            ]
        ]));

        $this->assertEquals(2, $obj->totalObjects);
        $this->assertEquals(100, $obj->maxSearchScore);
        $this->assertInternalType('array', $obj->objectRecords);
        $this->assertNotEmpty($obj->objectRecords);
        $this->assertInstanceOf(ObjectRecord::class, $obj->objectRecords[0]);
        $this->assertEquals(123, $obj->objectRecords[0]->objectRecordId);
    }
}
