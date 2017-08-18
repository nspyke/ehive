<?php

namespace EHive\Tests\Domain\ObjectRecord;

use EHive\Domain\ObjectRecord\Attribute;
use EHive\Domain\ObjectRecord\Field;

class FieldTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'k1',
                    'value' => 'v1',
                ],
            ],
        ]));

        $this->assertEquals('id', $obj->identifier);
        $this->assertInternalType('array', $obj->attributes);
        $this->assertNotEmpty($obj->attributes);
        $this->assertArrayHasKey('k1', $obj->attributes);
        $this->assertInstanceOf(Attribute::class, $obj->attributes['k1']);
        $this->assertEquals('k1', $obj->attributes['k1']->key);
        $this->assertEquals('v1', $obj->attributes['k1']->value);
    }

    public function testGetFieldAttribute()
    {
        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'k1',
                    'value' => 'v1',
                ],
            ],
        ]));

        $this->assertNull($obj->getFieldAttribute('foo'));
        $this->assertEquals('v1', $obj->getFieldAttribute('k1'));
    }

    public function testGetLabel()
    {
        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'label',
                    'value' => 'value',
                ],
            ],
        ]));

        $this->assertEquals('value', $obj->getLabel());

        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'foo',
                    'value' => 'value',
                ],
            ],
        ]));

        $this->assertNull($obj->getLabel());
    }

    public function testGetValue()
    {
        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'value',
                    'value' => 'value1',
                ],
            ],
        ]));

        $this->assertEquals('value1', $obj->getValue());

        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'foo',
                    'value' => 'value',
                ],
            ],
        ]));

        $this->assertNull($obj->getValue());
    }

    public function testGetHeight()
    {
        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'height',
                    'value' => 'value',
                ],
            ],
        ]));

        $this->assertEquals('value', $obj->getHeight());

        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'foo',
                    'value' => 'value',
                ],
            ],
        ]));

        $this->assertNull($obj->getHeight());
    }

    public function testGetWidth()
    {
        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'width',
                    'value' => 'value',
                ],
            ],
        ]));

        $this->assertEquals('value', $obj->getWidth());

        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'foo',
                    'value' => 'value',
                ],
            ],
        ]));

        $this->assertNull($obj->getWidth());
    }

    public function testGetUrl()
    {
        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'url',
                    'value' => 'value',
                ],
            ],
        ]));

        $this->assertEquals('value', $obj->getUrl());

        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'foo',
                    'value' => 'value',
                ],
            ],
        ]));

        $this->assertNull($obj->getUrl());
    }

    public function testGetIdentifier()
    {
        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'identifier',
                    'value' => 'value',
                ],
            ],
        ]));

        $this->assertEquals('value', $obj->getIdentifier());

        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'foo',
                    'value' => 'value',
                ],
            ],
        ]));

        $this->assertNull($obj->getIdentifier());
    }

    public function testGetTitle()
    {
        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'title',
                    'value' => 'value',
                ],
            ],
        ]));

        $this->assertEquals('value', $obj->getTitle());

        $obj = new Field(o([
            'identifier' => 'id',
            'attributes' => [
                [
                    'key' => 'foo',
                    'value' => 'value',
                ],
            ],
        ]));

        $this->assertNull($obj->getTitle());
    }
}
