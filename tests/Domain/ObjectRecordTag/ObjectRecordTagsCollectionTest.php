<?php

namespace EHive\Tests\Domain\ObjectRecordTag;

use EHive\Domain\ObjectRecordTag\ObjectRecordTag;
use EHive\Domain\ObjectRecordTag\ObjectRecordTagsCollection;

class ObjectRecordTagsCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new ObjectRecordTagsCollection(o([
            'objectRecordId' => 'objectRecordId',
            'allowTagging' => true,
            'objectRecordTags' => [
                [
                    'cleanTagName' => 'cleanTagName',
                    'rawTagName' => 'rawTagName'
                ]
            ]
        ]));

        $this->assertEquals('objectRecordId', $obj->objectRecordId);
        $this->assertTrue($obj->allowTagging);
        $this->assertInternalType('array', $obj->objectRecordTags);
        $this->assertNotEmpty($obj->objectRecordTags);
        $this->assertInstanceOf(ObjectRecordTag::class, $obj->objectRecordTags[0]);
        $this->assertEquals('cleanTagName', $obj->objectRecordTags[0]->cleanTagName);
    }
}
