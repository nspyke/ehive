<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
