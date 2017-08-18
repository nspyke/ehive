<?php
namespace EHive\Tests\Exception;

use EHive\Exception\UnauthorizedException;

class UnauthorizedExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $exception = new UnauthorizedException('abc');
        $this->assertEquals('ERROR MESSAGE: abc.', $exception->getMessage());
    }
}
