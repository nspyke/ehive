<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *
 * Copyright (C) 2012 Vernon Systems Limited
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and
 * to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions
 * of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
 * THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace EHive;

use EHive\Dao;
use EHive\Domain\ObjectRecordTag\ObjectRecordTag;
use EHive\Transport\TransportInterface;

class ApiClient
{
    const VERSION_ID = '/v2';

    /**
     * @var TransportInterface
     */
    protected $transport;

    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    //
    // Get Accounts
    //
    /**
     * @param $accountId
     *
     * @return Domain\Account\Account
     */
    public function getAccount($accountId)
    {
        $accountsDao = new Dao\Accounts($this->transport);
        $account = $accountsDao->getAccount($accountId);

        return $account;
    }

    /**
     * @param $communityId
     * @param $accountId
     *
     * @return Domain\Account\Account
     */
    public function getAccountInCommunity($communityId, $accountId)
    {
        $accountsDao = new Dao\Accounts($this->transport);
        $account = $accountsDao->getAccountInCommunity($communityId, $accountId);

        return $account;
    }

    /**
     * @param     $query
     * @param     $sort
     * @param     $direction
     * @param int $offset
     * @param int $limit
     *
     * @return Domain\Account\AccountsCollection
     */
    public function getAccountsInEHive($query, $sort = null, $direction = null, $offset = 0, $limit = 10)
    {
        $accountsDao = new Dao\Accounts($this->transport);
        $accountsCollection = $accountsDao->getAccountsInEHive($query, $sort, $direction, $offset, $limit);

        return $accountsCollection;
    }

    /**
     * @param     $communityId
     * @param     $query
     * @param     $sort
     * @param     $direction
     * @param int $offset
     * @param int $limit
     *
     * @return Domain\Account\AccountsCollection
     */
    public function getAccountsInCommunity($communityId, $query, $sort = null, $direction = null, $offset = 0, $limit = 10)
    {
        $accountsDao = new Dao\Accounts($this->transport);
        $accountsCollection = $accountsDao->getAccountsInCommunity(
            $communityId,
            $query,
            $sort,
            $direction,
            $offset,
            $limit
        );

        return $accountsCollection;
    }

    //
    //	Get Communities
    //
    /**
     * @param $accountId
     *
     * @return Domain\Community\CommunitiesCollection
     */
    public function getCommunitiesModeratoredByAccount($accountId)
    {
        $communitiesDao = new Dao\Communities($this->transport);
        $communitiesCollection = $communitiesDao->getCommunitiesModeratedByAccount($accountId);

        return $communitiesCollection;
    }

    /**
     * @param     $query
     * @param     $sort
     * @param     $direction
     * @param int $offset
     * @param int $limit
     *
     * @return Domain\Community\CommunitiesCollection
     */
    public function getCommunitiesInEHive($query, $sort = null, $direction = null, $offset = 0, $limit = 10)
    {
        $communitiesDao = new Dao\Communities($this->transport);
        $communitiesCollection = $communitiesDao->getCommunitiesInEHive($query, $sort, $direction, $offset, $limit);

        return $communitiesCollection;
    }


    //
    //	Get ObjectRecords
    //
    /**
     * @param $objectRecordId
     *
     * @return Domain\ObjectRecord\ObjectRecord
     */
    public function getObjectRecord($objectRecordId)
    {
        $objectRecordsDao = new Dao\ObjectRecords($this->transport);
        $objectRecords = $objectRecordsDao->getObjectRecord($objectRecordId);

        return $objectRecords;
    }

    /**
     * @param      $query
     * @param bool $hasImages
     * @param      $sort
     * @param      $direction
     * @param int  $offset
     * @param int  $limit
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getObjectRecordsInEHive($query, $hasImages = false, $sort = null, $direction = null, $offset = 0, $limit = 10)
    {
        $objectRecordsDao = new Dao\ObjectRecords($this->transport);
        $objectRecordsCollection = $objectRecordsDao->getObjectRecordsInEHive(
            $query,
            $hasImages,
            $sort,
            $direction,
            $offset,
            $limit
        );

        return $objectRecordsCollection;
    }

    /**
     * @param        $accountId
     * @param        $query
     * @param bool   $hasImages
     * @param        $sort
     * @param        $direction
     * @param int    $offset
     * @param int    $limit
     * @param string $content
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getObjectRecordsInAccount(
        $accountId,
        $query,
        $hasImages = false,
        $sort = null,
        $direction = null,
        $offset = 0,
        $limit = 10,
        $content = "public"
    ) {
        $objectRecordsDao = new Dao\ObjectRecords($this->transport);
        $objectRecordsCollection = $objectRecordsDao->getObjectRecordsInAccount(
            $accountId,
            $query,
            $hasImages,
            $sort,
            $direction,
            $offset,
            $limit,
            $content
        );

        return $objectRecordsCollection;
    }

    /**
     * @param      $communityId
     * @param      $query
     * @param bool $hasImages
     * @param      $sort
     * @param      $direction
     * @param int  $offset
     * @param int  $limit
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getObjectRecordsInCommunity(
        $communityId,
        $query,
        $hasImages = false,
        $sort = null,
        $direction = null,
        $offset = 0,
        $limit = 10
    ) {
        $objectRecordsDao = new Dao\ObjectRecords($this->transport);
        $objectRecordsCollection = $objectRecordsDao->getObjectRecordsInCommunity(
            $communityId,
            $query,
            $hasImages,
            $sort,
            $direction,
            $offset,
            $limit
        );

        return $objectRecordsCollection;
    }

    /**
     * @param      $communityId
     * @param      $accountId
     * @param      $query
     * @param bool $hasImages
     * @param      $sort
     * @param      $direction
     * @param int  $offset
     * @param int  $limit
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getObjectRecordsInAccountInCommunity(
        $communityId,
        $accountId,
        $query,
        $hasImages = false,
        $sort = null,
        $direction = null,
        $offset = 0,
        $limit = 10
    ) {
        $objectRecordsDao = new Dao\ObjectRecords($this->transport);
        $objectRecordsCollection = $objectRecordsDao->getObjectRecordsInAccountInCommunity(
            $communityId,
            $accountId,
            $query,
            $hasImages,
            $sort,
            $direction,
            $offset,
            $limit
        );

        return $objectRecordsCollection;
    }

    //
    // Get Interesting Object Records
    //
    /**
     * @param bool   $hasImages
     * @param string $catalogueType
     * @param int    $offset
     * @param int    $limit
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getInterestingObjectRecordsInEHive(
        $hasImages = false,
        $catalogueType = "",
        $offset = 0,
        $limit = 10
    ) {
        $interestingObjectRecordsDao = new Dao\InterestingObjectRecords($this->transport);
        $interestingObjectRecords = $interestingObjectRecordsDao->getInterestingObjectRecordsInEHive(
            $hasImages,
            $catalogueType,
            $offset,
            $limit
        );

        return $interestingObjectRecords;
    }

    /**
     * @param        $accountId
     * @param string $catalogueType
     * @param bool   $hasImages
     * @param int    $offset
     * @param int    $limit
     * @param string $content
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getInterestingObjectRecordsInAccount(
        $accountId,
        $catalogueType = "",
        $hasImages = false,
        $offset = 0,
        $limit = 10,
        $content = "public"
    ) {
        $interestingObjectRecordsDao = new Dao\InterestingObjectRecords($this->transport);
        $interestingObjectRecords = $interestingObjectRecordsDao->getInterestingObjectRecordsInAccount(
            $accountId,
            $catalogueType,
            $hasImages,
            $offset,
            $limit,
            $content
        );

        return $interestingObjectRecords;
    }

    /**
     * @param        $communityId
     * @param string $catalogueType
     * @param bool   $hasImages
     * @param int    $offset
     * @param int    $limit
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getInterestingObjectRecordsInCommunity(
        $communityId,
        $catalogueType = "",
        $hasImages = false,
        $offset = 0,
        $limit = 10
    ) {
        $interestingObjectRecordsDao = new Dao\InterestingObjectRecords($this->transport);
        $interestingObjectRecords = $interestingObjectRecordsDao->getInterestingObjectRecordsInCommunity(
            $communityId,
            $catalogueType,
            $hasImages,
            $offset,
            $limit
        );

        return $interestingObjectRecords;
    }

    /**
     * @param        $communityId
     * @param        $accountId
     * @param string $catalogueType
     * @param bool   $hasImages
     * @param int    $offset
     * @param int    $limit
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getInterestingObjectRecordsInAccountInCommunity(
        $communityId,
        $accountId,
        $catalogueType = "",
        $hasImages = false,
        $offset = 0,
        $limit = 10
    ) {
        $interestingObjectRecordsDao = new Dao\InterestingObjectRecords($this->transport);
        $interestingObjectRecords = $interestingObjectRecordsDao->getInterestingObjectRecordsInAccountInCommunity(
            $communityId,
            $accountId,
            $catalogueType,
            $hasImages,
            $offset,
            $limit
        );

        return $interestingObjectRecords;
    }


    //
    // Get Popular Object Records
    //
    /**
     * @param string $catalogueType
     * @param bool   $hasImages
     * @param int    $offset
     * @param int    $limit
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getPopularObjectRecordsInEHive($catalogueType = "", $hasImages = false, $offset = 0, $limit = 10)
    {
        $popularObjectRecordsDao = new Dao\PopularObjectRecords($this->transport);
        $popularObjectRecords = $popularObjectRecordsDao->getPopularObjectRecordsInEHive(
            $catalogueType,
            $hasImages,
            $offset,
            $limit
        );

        return $popularObjectRecords;
    }

    /**
     * @param        $accountId
     * @param string $catalogueType
     * @param bool   $hasImages
     * @param int    $offset
     * @param int    $limit
     * @param string $content
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getPopularObjectRecordsInAccount(
        $accountId,
        $catalogueType = "",
        $hasImages = false,
        $offset = 0,
        $limit = 10,
        $content = "public"
    ) {
        $popularObjectRecordsDao = new Dao\PopularObjectRecords($this->transport);
        $popularObjectRecords = $popularObjectRecordsDao->getPopularObjectRecordsInAccount(
            $accountId,
            $catalogueType,
            $hasImages,
            $offset,
            $limit,
            $content
        );

        return $popularObjectRecords;
    }

    /**
     * @param        $communityId
     * @param string $catalogueType
     * @param bool   $hasImages
     * @param int    $offset
     * @param int    $limit
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getPopularObjectRecordsInCommunity(
        $communityId,
        $catalogueType = "",
        $hasImages = false,
        $offset = 0,
        $limit = 10
    ) {
        $popularObjectRecordsDao = new Dao\PopularObjectRecords($this->transport);
        $popularObjectRecords = $popularObjectRecordsDao->getPopularObjectRecordsInCommunity(
            $communityId,
            $catalogueType,
            $hasImages,
            $offset,
            $limit
        );

        return $popularObjectRecords;
    }

    /**
     * @param        $communityId
     * @param        $accountId
     * @param string $catalogueType
     * @param bool   $hasImages
     * @param int    $offset
     * @param int    $limit
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getPopularObjectRecordsInAccountInCommunity(
        $communityId,
        $accountId,
        $catalogueType = "",
        $hasImages = false,
        $offset = 0,
        $limit = 10
    ) {
        $popularObjectRecordsDao = new Dao\PopularObjectRecords($this->transport);
        $popularObjectRecords = $popularObjectRecordsDao->getPopularObjectRecordsInAccountInCommunity(
            $communityId,
            $accountId,
            $catalogueType,
            $hasImages,
            $offset,
            $limit
        );

        return $popularObjectRecords;
    }


    //
    // Get Recent Object Records
    //
    /**
     * @param string $catalogueType
     * @param bool   $hasImages
     * @param int    $offset
     * @param int    $limit
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getRecentObjectRecordsInEHive($catalogueType = "", $hasImages = false, $offset = 0, $limit = 10)
    {
        $recentObjectRecordsDao = new Dao\RecentObjectRecords($this->transport);
        $recentObjectRecords = $recentObjectRecordsDao->getRecentObjectRecordsInEHive(
            $catalogueType,
            $hasImages,
            $offset,
            $limit
        );

        return $recentObjectRecords;
    }

    /**
     * @param        $accountId
     * @param string $catalogueType
     * @param bool   $hasImages
     * @param int    $offset
     * @param int    $limit
     * @param string $content
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getRecentObjectRecordsInAccount(
        $accountId,
        $catalogueType = "",
        $hasImages = false,
        $offset = 0,
        $limit = 10,
        $content = "public"
    ) {
        $recentObjectRecordsDao = new Dao\RecentObjectRecords($this->transport);
        $recentObjectRecords = $recentObjectRecordsDao->getRecentObjectRecordsInAccount(
            $accountId,
            $catalogueType,
            $hasImages,
            $offset,
            $limit,
            $content
        );

        return $recentObjectRecords;
    }

    /**
     * @param        $communityId
     * @param string $catalogueType
     * @param bool   $hasImages
     * @param int    $offset
     * @param int    $limit
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getRecentObjectRecordsInCommunity(
        $communityId,
        $catalogueType = "",
        $hasImages = false,
        $offset = 0,
        $limit = 10
    ) {
        $recentObjectRecordsDao = new Dao\RecentObjectRecords($this->transport);
        $recentObjectRecords = $recentObjectRecordsDao->getRecentObjectRecordsInCommunity(
            $communityId,
            $catalogueType,
            $hasImages,
            $offset,
            $limit
        );

        return $recentObjectRecords;
    }

    /**
     * @param        $communityId
     * @param        $accountId
     * @param string $catalogueType
     * @param bool   $hasImages
     * @param int    $offset
     * @param int    $limit
     *
     * @return Domain\ObjectRecord\ObjectRecordsCollection
     */
    public function getRecentObjectRecordsInAccountInCommunity(
        $communityId,
        $accountId,
        $catalogueType = "",
        $hasImages = false,
        $offset = 0,
        $limit = 10
    ) {
        $recentObjectRecordsDao = new Dao\RecentObjectRecords($this->transport);
        $recentObjectRecords = $recentObjectRecordsDao->getRecentObjectRecordsInAccountInCommunity(
            $communityId,
            $accountId,
            $catalogueType,
            $hasImages,
            $offset,
            $limit
        );

        return $recentObjectRecords;
    }


    //
    // Object Comments
    //
    /**
     * @param $objectRecordId
     * @param $offset
     * @param $limit
     *
     * @return Domain\Comment\CommentsCollection
     */
    public function getObjectRecordComments($objectRecordId, $offset = 0, $limit = 10)
    {
        $commentsDao = new Dao\Comments($this->transport);
        $comments = $commentsDao->getObjectRecordComments($objectRecordId, $offset, $limit);

        return $comments;
    }

    /**
     * @param $objectRecordId
     * @param $comment
     *
     * @return Domain\Comment\Comment
     */
    public function addObjectRecordComment($objectRecordId, $comment)
    {
        $commentsDao = new Dao\Comments($this->transport);
        $comment = $commentsDao->addObjectRecordComment($objectRecordId, $comment);

        return $comment;
    }

    //
    //  Object Record Tags
    //
    /**
     * @param $objectRecordId
     *
     * @return Domain\ObjectRecordTag\ObjectRecordTagsCollection
     */
    public function getObjectRecordTags($objectRecordId)
    {
        $objectRecordTagsDao = new Dao\ObjectRecordTags($this->transport);
        $objectRecordTags = $objectRecordTagsDao->getObjectRecordTags($objectRecordId);

        return $objectRecordTags;
    }

    /**
     * @param $objectRecordId
     * @param $objectRecordTag
     *
     * @return Domain\ObjectRecordTag\ObjectRecordTag
     */
    public function addObjectRecordTag($objectRecordId, $objectRecordTag)
    {
        $objectRecordTagsDao = new Dao\ObjectRecordTags($this->transport);
        $objectRecordTag = $objectRecordTagsDao->addObjectRecordTag($objectRecordId, $objectRecordTag);

        return $objectRecordTag;
    }

    /**
     * @param int             $objectRecordId
     * @param ObjectRecordTag $tag
     *
     * @return Domain\ObjectRecordTag\ObjectRecordTag
     */
    public function deleteObjectRecordTag($objectRecordId, ObjectRecordTag $tag)
    {
        $objectRecordTagsDao = new Dao\ObjectRecordTags($this->transport);
        $objectRecordTag = $objectRecordTagsDao->deleteObjectRecordTag($objectRecordId, $tag);

        return $objectRecordTag;
    }


    //
    // Tag Clouds
    //
    /**
     * @param $limit
     *
     * @return Domain\TagCloud\TagCloud
     */
    public function getTagCloudInEHive($limit = 10)
    {
        $tagCloudDao = new Dao\TagCloud($this->transport);
        $tagCloud = $tagCloudDao->getTagCloudInEHive($limit);

        return $tagCloud;
    }

    /**
     * @param $accountId
     * @param $limit
     *
     * @return Domain\TagCloud\TagCloud
     */
    public function getTagCloudInAccount($accountId, $limit = 10)
    {
        $tagCloudDao = new Dao\TagCloud($this->transport);
        $tagCloud = $tagCloudDao->getTagCloudInAccount($accountId, $limit);

        return $tagCloud;
    }

    /**
     * @param $communityId
     * @param $limit
     *
     * @return Domain\TagCloud\TagCloud
     */
    public function getTagCloudInCommunity($communityId, $limit)
    {
        $tagCloudDao = new Dao\TagCloud($this->transport);
        $tagCloud = $tagCloudDao->getTagCloudInCommunity($communityId, $limit);

        return $tagCloud;
    }
}
