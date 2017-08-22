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

use EHive\Domain\ObjectRecord\ObjectRecord;
use EHive\Domain\ObjectRecord\ObjectRecordsCollection;
use EHive\ApiClient;
use EHive\Transport\TransportInterface;

class ObjectRecords
{

    private $transport;

    public function __construct(TransportInterface $transport)
    {

        $this->transport = $transport;
    }

    public function getObjectRecord($objectRecordId)
    {

        $path = ApiClient::VERSION_ID . "/objectrecords/{$objectRecordId}";
        $json = $this->transport->get($path);

        return new ObjectRecord($json);
    }

    public function getObjectRecordsInEHive($query, $hasImages, $sort, $direction, $offset, $limit)
    {

        $path = ApiClient::VERSION_ID . "/objectrecords";
        $queryString = Helper::getObjectsQueryString($query, $hasImages, $sort, $direction, $offset, $limit);
        $json = $this->transport->get($path, $queryString);

        return new ObjectRecordsCollection($json);
    }

    public function getObjectRecordsInAccount(
        $accountId,
        $query,
        $hasImages,
        $sort,
        $direction,
        $offset,
        $limit,
        $content
    ) {

        $path = ApiClient::VERSION_ID . "/accounts/{$accountId}/objectrecords";
        $queryString = Helper::getObjectsQueryString(
            $query,
            $hasImages,
            $sort,
            $direction,
            $offset,
            $limit,
            $content
        );
        $json = $this->transport->get($path, $queryString);

        return new ObjectRecordsCollection($json);
    }

    public function getObjectRecordsInCommunity($communityId, $query, $hasImages, $sort, $direction, $offset, $limit)
    {

        $path = ApiClient::VERSION_ID . "/communities/{$communityId}/objectrecords";
        $queryString = Helper::getObjectsQueryString($query, $hasImages, $sort, $direction, $offset, $limit);
        $json = $this->transport->get($path, $queryString);

        return new ObjectRecordsCollection($json);
    }

    public function getObjectRecordsInAccountInCommunity(
        $communityId,
        $accountId,
        $query,
        $hasImages,
        $sort,
        $direction,
        $offset,
        $limit
    ) {

        $path = ApiClient::VERSION_ID . "/communities/{$communityId}/accounts/{$accountId}/objectrecords";
        $queryString = Helper::getObjectsQueryString($query, $hasImages, $sort, $direction, $offset, $limit);
        $json = $this->transport->get($path, $queryString);

        return new ObjectRecordsCollection($json);
    }
}
