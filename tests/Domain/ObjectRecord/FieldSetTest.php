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
