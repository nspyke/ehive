<?php
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
