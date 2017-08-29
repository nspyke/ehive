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
