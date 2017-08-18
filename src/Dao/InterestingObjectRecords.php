<?php

/*
	Copyright (C) 2012 Vernon Systems Limited
	
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"),
	to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
	and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

namespace EHive\Dao;

use EHive\Domain\ObjectRecord\ObjectRecordsCollection;
use EHive\ApiClient;
use EHive\Transport\TransportInterface;

class InterestingObjectRecords
{
    private $transport;

    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    public function getInterestingObjectRecordsInEHive($hasImages, $catalogueType, $offset, $limit)
    {
        $path = ApiClient::VERSION_ID . "/objectrecords/interesting";
        $path = Helper::urlWithCatalogueType($path, $catalogueType);
        $queryString = Helper::getObjectsQueryString(null, $hasImages, null, null, $offset, $limit);
        $json = $this->transport->get($path, $queryString, true);

        return new ObjectRecordsCollection($json);
    }

    public function getInterestingObjectRecordsInAccount(
        $accountId,
        $catalogueType,
        $hasImages,
        $offset,
        $limit,
        $content
    ) {
        $path = ApiClient::VERSION_ID . "/accounts/{$accountId}/objectrecords/interesting";
        $path = Helper::urlWithCatalogueType($path, $catalogueType);
        $queryString = Helper::getObjectsQueryString(null, $hasImages, null, null, $offset, $limit, $content);
        $json = $this->transport->get($path, $queryString, true);

        return new ObjectRecordsCollection($json);
    }

    public function getInterestingObjectRecordsInCommunity($communityId, $catalogueType, $hasImages, $offset, $limit)
    {
        $path = ApiClient::VERSION_ID . "/communities/{$communityId}/objectrecords/interesting";
        $path = Helper::urlWithCatalogueType($path, $catalogueType);
        $queryString = Helper::getObjectsQueryString(null, $hasImages, null, null, $offset, $limit);
        $json = $this->transport->get($path, $queryString, true);

        return new ObjectRecordsCollection($json);
    }

    public function getInterestingObjectRecordsInAccountInCommunity(
        $communityId,
        $accountId,
        $catalogueType,
        $hasImages,
        $offset,
        $limit
    ) {
        $path = ApiClient::VERSION_ID . "/communities/{$communityId}/accounts/{$accountId}/objectrecords/interesting";
        $path = Helper::urlWithCatalogueType($path, $catalogueType);
        $queryString = Helper::getObjectsQueryString(null, $hasImages, null, null, $offset, $limit);
        $json = $this->transport->get($path, $queryString, true);

        return new ObjectRecordsCollection($json);
    }
}
