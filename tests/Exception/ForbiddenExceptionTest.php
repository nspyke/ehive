<?php
namespace EHive\Tests\Exception;

use EHive\Exception\ForbiddenException;

class ForbiddenExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $exception = new ForbiddenException('abc');
        $this->assertEquals('ERROR MESSAGE: abc.', $exception->getMessage());
    }
}
