<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AuthorisedExaminerDesignatedManagerUrlBuilder as UrlBuilder;
use PHPUnit_Framework_TestCase;

/**
 * Class AuthorisedExaminerDesignatedManagerUrlBuilderTest
 *
 * @package DvsaCommonTest\UrlBuilder
 */
class AuthorisedExaminerDesignatedManagerUrlBuilderTest extends PHPUnit_Framework_TestCase
{
    const UUID = 'uuid-uuid';

    private $url;

    public function setUp()
    {
        $this->url = 'authorised-examiner-designated-manager-application/';
    }

    public function test_toString_noParams_shouldBuildMainUrl()
    {
        $this->assertSame(
            substr($this->url, 0, -1),
            UrlBuilder::authorisedExaminerDesignatedManagerApplication()->toString()
        );
    }

    public function test_applicantDetails_withUuid_shouldBuildMainUrl()
    {
        $this->assertSame(
            $this->url . self::UUID . UrlBuilder::APPLICANT_DETAILS,
            $this->createUrlBuilder()->applicantDetails()->toString()
        );
    }

    public function test_convictions_withUuid_shouldBuildMainUrl()
    {
        $this->assertSame(
            $this->url . self::UUID . UrlBuilder::CONVICTIONS,
            $this->createUrlBuilder()->convictions()->toString()
        );
    }

    public function test_declaration_withUuid_shouldBuildMainUrl()
    {
        $this->assertSame(
            $this->url . self::UUID . UrlBuilder::DECLARATION,
            $this->createUrlBuilder()->declaration()->toString()
        );
    }

    /**
     * @return UrlBuilder
     */
    private function createUrlBuilder()
    {
        return UrlBuilder::authorisedExaminerDesignatedManagerApplication()->routeParam('uuid', self::UUID);
    }
}
