<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
