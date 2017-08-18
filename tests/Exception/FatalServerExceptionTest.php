<?php
namespace EHive\Tests\Exception;

use EHive\Exception\FatalServerException;

class FatalServerExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $exception = new FatalServerException('abc');
        $this->assertEquals('ERROR MESSAGE: abc.', $exception->getMessage());
    }
}
