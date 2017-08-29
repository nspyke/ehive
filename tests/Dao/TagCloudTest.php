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

use EHive\Dao\TagCloud;

class TagCloudTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $transport;

    public function testGetTagCloudInEHive()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode([
                'tagCloudTags' => [
                    ['percentage' => 100, 'cleanTagName' => 'abc']
                ]
            ])));

        $dao = new TagCloud($this->transport);

        $response = $dao->getTagCloudInEHive(1);
        $this->assertInstanceOf(\EHive\Domain\TagCloud\TagCloud::class, $response);
        $this->assertInternalType('array', $response->tagCloudTags);
        $this->assertInstanceOf(\EHive\Domain\TagCloud\TagCloudTag::class, $response->tagCloudTags[0]);
    }

    public function testGetTagCloudInAccount()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode([
                'tagCloudTags' => [
                    ['percentage' => 100, 'cleanTagName' => 'abc']
                ]
            ])));

        $dao = new TagCloud($this->transport);

        $response = $dao->getTagCloudInAccount(1, 1);
        $this->assertInstanceOf(\EHive\Domain\TagCloud\TagCloud::class, $response);
        $this->assertInternalType('array', $response->tagCloudTags);
        $this->assertInstanceOf(\EHive\Domain\TagCloud\TagCloudTag::class, $response->tagCloudTags[0]);
    }

    public function testGetTagCloudInCommunity()
    {
        $this->transport->expects($this->once())
            ->method('get')
            ->willReturn(json_decode(json_encode([
                'tagCloudTags' => [
                    ['percentage' => 100, 'cleanTagName' => 'abc']
                ]
            ])));

        $dao = new TagCloud($this->transport);

        $response = $dao->getTagCloudInCommunity(1, 1);
        $this->assertInstanceOf(\EHive\Domain\TagCloud\TagCloud::class, $response);
        $this->assertInternalType('array', $response->tagCloudTags);
        $this->assertInstanceOf(\EHive\Domain\TagCloud\TagCloudTag::class, $response->tagCloudTags[0]);
    }

    protected function setUp()
    {
        $this->transport = $this->getMockBuilder(\EHive\Transport\Transport::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
