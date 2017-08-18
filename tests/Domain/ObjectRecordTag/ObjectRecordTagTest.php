<?php

namespace EHive\Tests\Domain\ObjectRecordTag;

use EHive\Domain\ObjectRecordTag\ObjectRecordTag;

class ObjectRecordTagTest extends \PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $obj = new ObjectRecordTag(o([
            'cleanTagName' => 'cleanTagName',
            'rawTagName' => 'rawTagName'
        ]));

        $this->assertEquals('cleanTagName', $obj->cleanTagName);
        $this->assertEquals('rawTagName', $obj->rawTagName);
    }
}
