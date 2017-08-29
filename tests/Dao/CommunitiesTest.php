<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EHive\Tests\Dao;

use EHive\Dao\Communities;
use EHive\Domain\Community\CommunitiesCollection;

class CommunitiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $transport;

    public function testGetCommunitiesModeratedByAccount()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode([
                'communities' => [
                    [
                        'communityId' => 123
                    ]
                ]
            ])));

        $dao = new Communities($this->transport);

        $response = $dao->getCommunitiesModeratedByAccount(123);
        $this->assertInstanceOf(CommunitiesCollection::class, $response);
        $this->assertCount(1, $response->communities);
        $this->assertInstanceOf(\EHive\Domain\Community\Community::class, $response->communities[0]);
    }

    public function testGetCommunitiesInEHive()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode([
                'communities' => [
                    [
                        'communityId' => 123
                    ]
                ]
            ])));

        $dao = new Communities($this->transport);

        $response = $dao->getCommunitiesInEHive('abc');
        $this->assertInstanceOf(CommunitiesCollection::class, $response);
        $this->assertCount(1, $response->communities);
        $this->assertInstanceOf(\EHive\Domain\Community\Community::class, $response->communities[0]);
    }

    protected function setUp()
    {
        $this->transport = $this->getMockBuilder(\EHive\Transport\Transport::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
