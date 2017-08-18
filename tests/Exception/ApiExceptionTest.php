<?php

namespace EHive\Tests\Exception;

use EHive\Exception\ApiException;

class ApiExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $exception = new ApiException('abc');
        $this->assertEquals('abc', $exception->getMessage());
    }
}
