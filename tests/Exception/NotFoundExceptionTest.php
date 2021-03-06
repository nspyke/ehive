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

use EHive\Exception\NotFoundException;

class NotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $exception = new NotFoundException('abc');
        $this->assertEquals('ERROR MESSAGE: abc.', $exception->getMessage());
    }
}
