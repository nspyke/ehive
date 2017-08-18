<?php
namespace EHive\Tests\Dao;

use EHive\Dao\Helper;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $catalogueType
     *
     * @dataProvider dataProviderForUrlWithValidCatalogueType
     */
    public function testUrlWithValidCatalogueType($catalogueType)
    {
        $path = 'foo';
        $this->assertEquals($path . '/' . $catalogueType, Helper::urlWithCatalogueType($path, $catalogueType));
    }

    /**
     * @expectedException \EHive\Exception\ApiException
     */
    public function testUrlWithInvalidCatalogueType()
    {
        Helper::urlWithCatalogueType('foo', 'bar');
    }

    public function dataProviderForUrlWithValidCatalogueType()
    {
        return [
            ['archives'],
            ['archaeology'],
            ['art'],
            ['history'],
            ['library'],
            ['natural_science'],
            ['photography'],
            [''],
        ];
    }

    /**
     * @param null $query
     * @param null $sort
     * @param null $direction
     * @param null $offset
     * @param null $limit
     *
     * @dataProvider dataProviderForGetQueryString
     */
    public function testGetAccountsQueryString(
        $query = null,
        $sort = null,
        $direction = null,
        $offset = null,
        $limit = null
    ) {
        $str = Helper::getAccountsQueryString($query, $sort, $direction, $offset, $limit);
        $result = [];
        parse_str($str, $result);

        if (isset($query)) {
            $this->assertEquals($query, $result['query']);
        } else {
            $this->assertArrayNotHasKey('query', $result);
        }

        if (isset($sort)) {
            $this->assertEquals($sort, $result['sort']);
        } else {
            $this->assertArrayNotHasKey('sort', $result);
        }

        if (isset($direction)) {
            $this->assertEquals($direction, $result['direction']);
        } else {
            $this->assertArrayNotHasKey('direction', $result);
        }

        if (isset($offset)) {
            $this->assertEquals($offset, $result['offset']);
        } else {
            $this->assertArrayNotHasKey('offset', $result);
        }

        if (isset($limit)) {
            $this->assertEquals($limit, $result['limit']);
        } else {
            $this->assertArrayNotHasKey('limit', $result);
        }

        if ($query === null and $sort === null and $direction === null and $offset === null and $limit === null) {
            $this->assertEmpty($str);
        }
    }

    /**
     * @param null $query
     * @param null $sort
     * @param null $direction
     * @param null $offset
     * @param null $limit
     *
     * @dataProvider dataProviderForGetQueryString
     */
    public function testGetCommunitiesQueryString(
        $query = null,
        $sort = null,
        $direction = null,
        $offset = null,
        $limit = null
    ) {
        $str = Helper::getCommunitiesQueryString($query, $sort, $direction, $offset, $limit);
        $result = [];
        parse_str($str, $result);

        if (isset($query)) {
            $this->assertEquals($query, $result['query']);
        } else {
            $this->assertArrayNotHasKey('query', $result);
        }

        if (isset($sort)) {
            $this->assertEquals($sort, $result['sort']);
        } else {
            $this->assertArrayNotHasKey('sort', $result);
        }

        if (isset($direction)) {
            $this->assertEquals($direction, $result['direction']);
        } else {
            $this->assertArrayNotHasKey('direction', $result);
        }

        if (isset($offset)) {
            $this->assertEquals($offset, $result['offset']);
        } else {
            $this->assertArrayNotHasKey('offset', $result);
        }

        if (isset($limit)) {
            $this->assertEquals($limit, $result['limit']);
        } else {
            $this->assertArrayNotHasKey('limit', $result);
        }

        if ($query === null and $sort === null and $direction === null and $offset === null and $limit === null) {
            $this->assertEmpty($str);
        }
    }

    public function dataProviderForGetQueryString()
    {
        return [
            [],
            ['abc'],
            ['abc', 'def'],
            ['abc', 'def', 'hij'],
            ['abc', 'def', 'hij', 'klm'],
            ['abc', 'def', 'hij', 'klm', 'nop'],
            [null, 'def'],
            [null, 'def', 'hij'],
            [null, 'def', 'hij', 'klm'],
            [null, 'def', 'hij', 'klm', 'nop'],
            [null, null, 'hij'],
            [null, null, 'hij', 'klm'],
            [null, null, 'hij', 'klm', 'nop'],
            [null, null, null, 'klm'],
            [null, null, null, 'klm', 'nop'],
            [null, null, null, null, 'nop'],
        ];
    }

    /**
     * @param      $query
     * @param      $hasImages
     * @param      $sort
     * @param      $direction
     * @param      $offset
     * @param      $limit
     * @param null $content
     *
     * @dataProvider dataProviderForGetObjectsQueryString
     */
    public function testGetObjectsQueryString(
        $query = null,
        $hasImages = null,
        $sort = null,
        $direction = null,
        $offset = null,
        $limit = null,
        $content = null
    ) {
        $str = Helper::getObjectsQueryString($query, $hasImages, $sort, $direction, $offset, $limit, $content);
        $result = [];
        parse_str($str, $result);

        if ($hasImages === 'true' or $hasImages === true) {
            $this->assertEquals('true', $result['hasImages']);
        }

        if ($hasImages === 'false' or $hasImages === false) {
            $this->assertEquals('false', $result['hasImages']);
        }

        if (isset($query)) {
            $this->assertEquals($query, $result['query']);
        } else {
            $this->assertArrayNotHasKey('query', $result);
        }

        if (isset($sort)) {
            $this->assertEquals($sort, $result['sort']);
        } else {
            $this->assertArrayNotHasKey('sort', $result);
        }

        if (isset($direction)) {
            $this->assertEquals($direction, $result['direction']);
        } else {
            $this->assertArrayNotHasKey('direction', $result);
        }

        if (isset($offset)) {
            $this->assertEquals($offset, $result['offset']);
        } else {
            $this->assertArrayNotHasKey('offset', $result);
        }

        if (isset($limit)) {
            $this->assertEquals($limit, $result['limit']);
        } else {
            $this->assertArrayNotHasKey('limit', $result);
        }

        if (isset($content)) {
            $this->assertEquals($content, $result['content']);
        } else {
            $this->assertArrayNotHasKey('content', $result);
        }
    }

    /**
     * @expectedException \EHive\Exception\ApiException
     */
    public function testGetObjectsQueryStringWithNullHasImages()
    {
        Helper::getObjectsQueryString('foo', null, null, null, null, null);
    }

    /**
     * @expectedException \EHive\Exception\ApiException
     */
    public function testGetObjectsQueryStringWithNonTrueFalseHasImages()
    {
        echo Helper::getObjectsQueryString('foo', 'abc', null, null, null, null);
    }

    public function dataProviderForGetObjectsQueryString()
    {
        return [
            [null, true],
            [null, 'true'],
            [null, false],
            [null, 'false'],

            ['abc', true],
            ['abc', 'true'],
            ['abc', false],
            ['abc', 'false'],

            ['abc', 'true', 'def'],
            ['abc', 'true', 'hij', 'klm'],
            ['abc', 'true', 'hij', 'klm', 'nop'],
            ['abc', 'true', 'hij', 'klm', 'nop', 'qrs'],
            ['abc', 'true', 'hij', 'klm', 'nop', 'qrs', 'tuv'],
            [null, 'true', 'hij', 'klm', 'nop', 'qrs', 'tuv'],
            [null, 'true', null, 'klm', 'nop', 'qrs', 'tuv'],
            [null, 'true', null, null, 'nop', 'qrs', 'tuv'],
            [null, 'true', null, null, null, 'qrs', 'tuv'],
            [null, 'true', null, null, null, null, 'tuv'],
        ];
    }
}
