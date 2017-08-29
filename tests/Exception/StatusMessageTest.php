<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EHive\Tests\Exception;

use EHive\Exception\StatusMessage;

class StatusMessageTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $exception = new StatusMessage(
            json_decode(json_encode([
                'code' => 1,
                'message' => 'abc',
                'details' => 'def',
                'technicalDetails' => 'foobar'
            ]))
        );
        $this->assertEquals(
            'ERROR CODE: 1. ERROR MESSAGE: abc. ERROR DETAILS: def. TECHNICAL DETAILS: foobar',
            (string)$exception
        );
        $this->assertEquals(
            'ERROR CODE: 1. ERROR MESSAGE: abc. ERROR DETAILS: def. TECHNICAL DETAILS: foobar',
            $exception->toString()
        );
    }
}
