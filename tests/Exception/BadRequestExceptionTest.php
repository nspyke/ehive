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

use EHive\Exception\BadRequestException;

class BadRequestExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $exception = new BadRequestException('abc');
        $this->assertEquals('abc', $exception->getMessage());
    }
}
