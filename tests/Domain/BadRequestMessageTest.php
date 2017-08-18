<?php
namespace EHive\Tests\Domain;

use EHive\Domain\BadRequestMessage;
use EHive\Domain\RequestField;

class BadRequestMessageTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new BadRequestMessage(o([
            'requestMessage' => 'abc',
            'requestFields' => [
                ['fieldName' => 'a', 'fieldMessage' => 'b'],
            ],
        ]));
        $this->assertEquals('abc', $obj->requestMessage);
        $this->assertNotEmpty($obj->requestFields);
        $this->assertInstanceOf(RequestField::class, $obj->requestFields[0]);
    }
}
