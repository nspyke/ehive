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
