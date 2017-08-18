<?php

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
