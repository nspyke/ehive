<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EHive\Tests\Domain\Community;

use EHive\Domain\Community\CommunitiesCollection;
use EHive\Domain\Community\Community;

class CommunitiesCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new CommunitiesCollection(o([
            'totalCommunities' => 10,
            'maxSearchScore' => 20,
            'communities' => [
                [
                    'communityId' => 'communityId'
                ]
            ]
        ]));

        $this->assertEquals(10, $obj->totalCommunities);
        $this->assertEquals(20, $obj->maxSearchScore);
        $this->assertInternalType('array', $obj->communities);
        $this->assertNotEmpty($obj->communities);
        $this->assertInstanceOf(Community::class, $obj->communities[0]);
        $this->assertEquals('communityId', $obj->communities[0]->communityId);
    }
}
