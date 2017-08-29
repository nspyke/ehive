<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EHive\Tests\Domain\Account;

use EHive\Domain\Account\Account;
use EHive\Domain\ObjectRecord\MediaSet;

class AccountTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new Account(o([
            'accountId' => 'accountId',
            'publicProfileName' => 'publicProfileName',
            'shortProfileName' => 'shortProfileName',
            'physicalAddress' => 'physicalAddress',
            'postalAddress' => 'postalAddress',
            'phoneNumber' => 'phoneNumber',
            'emailAddress' => 'emailAddress',
            'facsimile' => 'facsimile',
            'website' => 'website',
            'hoursOfOperation' => 'hoursOfOperation',
            'admissionCharges' => 'admissionCharges',
            'staffDetails' => 'staffDetails',
            'aboutCollection' => 'aboutCollection',
            'wheelChairAccessFacility' => 'wheelChairAccessFacility',
            'cafeFacility' => 'cafeFacility',
            'referenceLibraryFacility' => 'referenceLibraryFacility',
            'parkingFacility' => 'parkingFacility',
            'shopFacility' => 'shopFacility',
            'functionSpaceFacility' => 'functionSpaceFacility',
            'guidedTourFacility' => 'guidedTourFacility',
            'publicProgrammesFacility' => 'publicProgrammesFacility',
            'membershipClubFacility' => 'membershipClubFacility',
            'toiletFacility' => 'toiletFacility',
            'otherFacility' => 'otherFacility',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'zoomLevel' => 'zoomLevel',
        ]));

        $this->assertEquals('accountId', $obj->accountId);
        $this->assertEquals('publicProfileName', $obj->publicProfileName);
        $this->assertEquals('shortProfileName', $obj->shortProfileName);
        $this->assertEquals('physicalAddress', $obj->physicalAddress);
        $this->assertEquals('postalAddress', $obj->postalAddress);
        $this->assertEquals('phoneNumber', $obj->phoneNumber);
        $this->assertEquals('emailAddress', $obj->emailAddress);
        $this->assertEquals('facsimile', $obj->facsimile);
        $this->assertEquals('website', $obj->website);
        $this->assertEquals('hoursOfOperation', $obj->hoursOfOperation);
        $this->assertEquals('admissionCharges', $obj->admissionCharges);
        $this->assertEquals('staffDetails', $obj->staffDetails);
        $this->assertEquals('aboutCollection', $obj->aboutCollection);
        $this->assertEquals('wheelChairAccessFacility', $obj->wheelChairAccessFacility);
        $this->assertEquals('cafeFacility', $obj->cafeFacility);
        $this->assertEquals('referenceLibraryFacility', $obj->referenceLibraryFacility);
        $this->assertEquals('parkingFacility', $obj->parkingFacility);
        $this->assertEquals('shopFacility', $obj->shopFacility);
        $this->assertEquals('functionSpaceFacility', $obj->functionSpaceFacility);
        $this->assertEquals('guidedTourFacility', $obj->guidedTourFacility);
        $this->assertEquals('publicProgrammesFacility', $obj->publicProgrammesFacility);
        $this->assertEquals('membershipClubFacility', $obj->membershipClubFacility);
        $this->assertEquals('toiletFacility', $obj->toiletFacility);
        $this->assertEquals('otherFacility', $obj->otherFacility);
        $this->assertEquals('latitude', $obj->latitude);
        $this->assertEquals('longitude', $obj->longitude);
        $this->assertEquals('zoomLevel', $obj->zoomLevel);
    }

    public function testGetMediaSetByIdentifier()
    {
        $obj = new Account(o([
            'mediaSets' => [
                [
                    'identifier' => 'foo',
                    'mediaRows' => [],
                ],
            ],
        ]));

        $this->assertInstanceOf(MediaSet::class, $obj->getMediaSetByIdentifier('foo'));
        $this->assertNull($obj->getMediaSetByIdentifier('bar'));
    }
}
