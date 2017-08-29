<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EHive\Tests\Domain\ObjectRecord;

use EHive\Domain\Account\Account;
use EHive\Domain\ObjectRecord\FieldSet;
use EHive\Domain\ObjectRecord\MediaSet;
use EHive\Domain\ObjectRecord\ObjectRecord;

class ObjectRecordTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new ObjectRecord(o([
            'objectRecordId' => 'objectRecordId',
            'externalId' => 'externalId',
            'objectUrl' => 'objectUrl',
            'slug' => 'slug',
            'catalogueType' => 'catalogueType',
            'metadataRights' => 'metadataRights',
            'accountId' => 'accountId',
            'searchScore' => 'searchScore',
            'account' => [
                'accountId' => 123,
            ],
            'mediaSets' => [
                ['identifier' => 'mediaSetId1'],
            ],
            'fieldSets' => [
                ['identifier' => 'fieldSetId1'],
            ],
        ]));

        $this->assertEquals('objectRecordId', $obj->objectRecordId);
        $this->assertEquals('externalId', $obj->externalId);
        $this->assertEquals('objectUrl', $obj->objectUrl);
        $this->assertEquals('slug', $obj->slug);
        $this->assertEquals('catalogueType', $obj->catalogueType);
        $this->assertEquals('metadataRights', $obj->metadataRights);
        $this->assertEquals('accountId', $obj->accountId);
        $this->assertEquals('searchScore', $obj->searchScore);

        $this->assertInstanceOf(Account::class, $obj->account);
        $this->assertEquals(123, $obj->account->accountId);

        $this->assertInternalType('array', $obj->mediaSets);
        $this->assertInstanceOf(MediaSet::class, $obj->mediaSets['mediaSetId1']);
        $this->assertEquals('mediaSetId1', $obj->mediaSets['mediaSetId1']->identifier);

        $this->assertInternalType('array', $obj->fieldSets);
        $this->assertInstanceOf(FieldSet::class, $obj->fieldSets['fieldSetId1']);
        $this->assertEquals('fieldSetId1', $obj->fieldSets['fieldSetId1']->identifier);
    }

    public function testGetFieldSetByIdentifier()
    {
        $obj = new ObjectRecord(o([
            'objectRecordId' => 'objectRecordId',
            'externalId' => 'externalId',
            'objectUrl' => 'objectUrl',
            'slug' => 'slug',
            'catalogueType' => 'catalogueType',
            'metadataRights' => 'metadataRights',
            'accountId' => 'accountId',
            'searchScore' => 'searchScore',
            'account' => [
                'accountId' => 123,
            ],
            'mediaSets' => [
                ['identifier' => 'mediaSetId1'],
            ],
            'fieldSets' => [
                ['identifier' => 'fieldSetId1'],
            ],
        ]));

        $this->assertInstanceOf(FieldSet::class, $obj->getFieldSetByIdentifier('fieldSetId1'));
        $this->assertNull($obj->getFieldSetByIdentifier('foobar'));
    }

    public function testGetMediaSetByIdentifier()
    {
        $obj = new ObjectRecord(o([
            'objectRecordId' => 'objectRecordId',
            'externalId' => 'externalId',
            'objectUrl' => 'objectUrl',
            'slug' => 'slug',
            'catalogueType' => 'catalogueType',
            'metadataRights' => 'metadataRights',
            'accountId' => 'accountId',
            'searchScore' => 'searchScore',
            'account' => [
                'accountId' => 123,
            ],
            'mediaSets' => [
                ['identifier' => 'mediaSetId1'],
            ],
            'fieldSets' => [
                ['identifier' => 'fieldSetId1'],
            ],
        ]));

        $this->assertInstanceOf(MediaSet::class, $obj->getMediaSetByIdentifier('mediaSetId1'));
        $this->assertNull($obj->getMediaSetByIdentifier('foo'));
    }
}
