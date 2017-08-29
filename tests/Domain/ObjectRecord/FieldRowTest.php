<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EHive\Tests\Domain\ObjectRecord;

use EHive\Domain\ObjectRecord\Field;
use EHive\Domain\ObjectRecord\FieldRow;

class FieldRowTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new FieldRow(o([
            'fields' => [
                [
                    'identifier' => 'id1'
                ],
                [
                    'identifier' => 'id2'
                ],
            ]
        ]));

        $this->assertInternalType('array', $obj->fields);
        $this->assertNotEmpty($obj->fields);
        $this->assertArrayHasKey('id1', $obj->fields);
        $this->assertArrayHasKey('id2', $obj->fields);
        $this->assertInstanceOf(Field::class, $obj->fields['id1']);
        $this->assertInstanceOf(Field::class, $obj->fields['id2']);
    }

    public function testGetFieldByIdentifier()
    {
        $obj = new FieldRow(o([
            'fields' => [
                [
                    'identifier' => 'id1',
                    'attributes' => [
                        [
                            'key' => 'a1',
                            'value' => 'val'
                        ]
                    ]
                ],
            ]
        ]));

        $this->assertInstanceOf(Field::class, $obj->getFieldByIdentifier('id1'));
        $this->assertNull($obj->getFieldByIdentifier('bar'));
    }
}
