# EHive API Client

[![Build Status](https://travis-ci.org/nspyke/ehive.svg?branch=master)](https://travis-ci.org/nspyke/ehive)

Fork of the EHive PHP client version 2.1.2 released by Vernon Systems in August 2017, under the GPL v2 licence.
This library has been released under GPL v3 licence.

This fork adds namespacing, Packagist and Composer support, PSR-4 auto-loading, PSR-16 caching, PSR-3 logging, 
and 90%+ unit test coverage.

**Usage**

```php
use EHive\ApiClient;
use EHive\Transport\Transport;

$transport = new Transport(
    'your_client_id',
    'your_client_secret',
    'your_tracking_id'
);
$client = new ApiClient($transport);
```

Then call the methods on the ApiClient object to get the relevant domain object.

#### Caching
[PSR-16](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-16-simple-cache.md) simple caching has been 
implemented to cache GET requests. We recommend [symfony/cache](https://packagist.org/packages/symfony/cache), but
any implementation can be used. You can find implementations by looking for packages providing 
the [psr/simple-cache-implementation](https://packagist.org/providers/psr/simple-cache-implementation) virtual package.

**Usage**
```php
$cache = new MyCache(); // which implements \Psr\SimpleCache\CacheInterface
$transport = new Transport(...);
$transport->setCache($cache);
$client = new ApiClient($transport);
```

#### Logging
[PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) logging has also been
implemented to log errors. We recommend using [monolog/monolog](https://packagist.org/packages/monolog/monolog).

**Usage**
```php
$logger = new MyLogger(); // which implements \Psr\Log\LoggerInterface
$transport = new Transport(...);
$transport->setLogger($logger);
$client = new ApiClient($transport);
```

## API Client Methods

#### Accounts
```php
/** @var EHive\Domain\Account\Account $account */
$account = $client->getAccount($accountId);
$account = $client->getAccountInCommunity($communityId, $accountId);

/** @var EHive\Domain\Account\AccountsCollection $accountsCollection */
$accountsCollection = $client->getAccountsInEHive($query, $sort, $direction, $offset = 0, $limit = 10);
$accountsCollection = $client->getAccountsInCommunity($communityId, $query, $sort, $direction, $offset = 0, $limit = 10);
```

#### Communities
```php
/** @var EHive\Domain\Community\CommunitiesCollection $communitiesCollection */
$communitiesCollection = $client->getCommunitiesModeratoredByAccount($accountId);
$communitiesCollection = $client->getCommunitiesInEHive($query, $sort, $direction, $offset = 0, $limit = 10);
```

#### ObjectRecords
```php
/** @var EHive\Domain\ObjectRecord\ObjectRecord $objectRecord */
$objectRecord = $client->getObjectRecord($objectRecordId);

/** @var EHive\Domain\ObjectRecord\ObjectRecordsCollection $objectRecordsCollection */
$objectRecordsCollection = $client->getObjectRecordsInEHive($query, $hasImages = false, $sort, $direction, $offset = 0, $limit = 10);
$objectRecordsCollection = $client->getObjectRecordsInAccount($accountId, $query, $hasImages = false, $sort, $direction, $offset = 0, $limit = 10, $content = "public");
$objectRecordsCollection = $client->getObjectRecordsInCommunity($communityId, $query, $hasImages = false, $sort, $direction, $offset = 0, $limit = 10);
$objectRecordsCollection = $client->getObjectRecordsInAccountInCommunity($communityId, $accountId, $query, $hasImages = false, $sort, $direction, $offset = 0, $limit = 10);
```

#### Interesting Object Records
```php
/** @var EHive\Domain\ObjectRecord\ObjectRecordsCollection $objectRecordsCollection */
$objectRecordsCollection = $client->getInterestingObjectRecordsInEHive($hasImages = false, $catalogueType = "", $offset = 0, $limit = 10);
$objectRecordsCollection = $client->getInterestingObjectRecordsInAccount($accountId, $catalogueType = "", $hasImages = false, $offset = 0, $limit = 10, $content = "public");
$objectRecordsCollection = $client->getInterestingObjectRecordsInCommunity($communityId, $catalogueType = "", $hasImages = false, $offset = 0, $limit = 10);
$objectRecordsCollection = $client->getInterestingObjectRecordsInAccountInCommunity($communityId, $accountId, $catalogueType = "", $hasImages = false, $offset = 0, $limit = 10);
```

#### Popular Object Records
```php
/** @var EHive\Domain\ObjectRecord\ObjectRecordsCollection $objectRecordsCollection */
$objectRecordsCollection = $client->getPopularObjectRecordsInEHive($hasImages = false, $catalogueType = "", $offset = 0, $limit = 10);
$objectRecordsCollection = $client->getPopularObjectRecordsInAccount($accountId, $catalogueType = "", $hasImages = false, $offset = 0, $limit = 10, $content = "public");
$objectRecordsCollection = $client->getPopularObjectRecordsInCommunity($communityId, $catalogueType = "", $hasImages = false, $offset = 0, $limit = 10);
$objectRecordsCollection = $client->getPopularObjectRecordsInAccountInCommunity($communityId, $accountId, $catalogueType = "", $hasImages = false, $offset = 0, $limit = 10);
```

#### Recent Object Records
```php
/** @var EHive\Domain\ObjectRecord\ObjectRecordsCollection $objectRecordsCollection */
$objectRecordsCollection = $client->getRecentObjectRecordsInEHive($hasImages = false, $catalogueType = "", $offset = 0, $limit = 10);
$objectRecordsCollection = $client->getRecentObjectRecordsInAccount($accountId, $catalogueType = "", $hasImages = false, $offset = 0, $limit = 10, $content = "public");
$objectRecordsCollection = $client->getRecentObjectRecordsInCommunity($communityId, $catalogueType = "", $hasImages = false, $offset = 0, $limit = 10);
$objectRecordsCollection = $client->getRecentObjectRecordsInAccountInCommunity($communityId, $accountId, $catalogueType = "", $hasImages = false, $offset = 0, $limit = 10);
```


#### Object Comments
```php
/** @var EHive\Domain\Comment\CommentsCollection $commentsCollection */
$commentsCollection = $client->getObjectRecordComments($objectRecordId, $offset = 0, $limit = 10);

/** @var EHive\Domain\Comment\Comment $comment */
// Create and set the object and its values via the public properties
$comment = new Comment();
...
$comment = $client->addObjectRecordComment($objectRecordId, $comment);
```

#### Object Record Tags
```php
/** @var EHive\Domain\ObjectRecordTag\ObjectRecordTagsCollection $objectRecordTagsCollection */
$objectRecordTagsCollection = $client->getObjectRecordTags($objectRecordId);

/** @var EHive\Domain\ObjectRecordTag\ObjectRecordTag $objectRecordTag */
// Create and set the object and its values via the public properties
$tag = new ObjectRecordTag();
...
$objectRecordTag = $client->addObjectRecordTag($objectRecordId, $tag);
$objectRecordTag = $client->deleteObjectRecordTag($objectRecordId, ObjectRecordTag $tag);
```

#### Tag Clouds
```php
/** @var EHive\Domain\TagCloud\TagCloud $tagCloud */
$tagCloud = $client->getTagCloudInEHive($limit = 10);
$tagCloud = $client->getTagCloudInAccount($accountId, $limit = 10);
$tagCloud = $client->getTagCloudInCommunity($communityId, $limit = 10);
```
