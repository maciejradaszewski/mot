<?php

namespace DvsaClientTest\Mapper;

use DvsaClient\Mapper\OrganisationSitesMapper;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilder;

class OrganisationSitesMapperTest extends AbstractMapperTest
{
    const ORG_ID = 9999;
    const SITE_NR = 'SN00099';
    const LINK_ID = 11111;

    const API_RETURN = 'apiReturn';

    /** @var $mapper OrganisationSitesMapper */
    private $mapper;

    public function setUp()
    {
        parent::setUp();

        $this->mapper = new OrganisationSitesMapper($this->client);
    }

    public function testFetchAllForOrganisation()
    {
        $this->setupClientMockGet(
            AuthorisedExaminerUrlBuilder::site(self::ORG_ID),
            ['data' => self::API_RETURN]
        );

        $this->assertSame(self::API_RETURN, $this->mapper->fetchAllForOrganisation(self::ORG_ID));
    }

    public function testFetchAllUnlinkedSites()
    {
        $this->setupClientMockGet(
            AuthorisedExaminerUrlBuilder::siteLink(),
            ['data' => self::API_RETURN]
        );

        $this->assertSame(self::API_RETURN, $this->mapper->fetchAllUnlinkedSites());
    }

    public function testCreateSiteLink()
    {
        $apiReturn = 'apiReturn';

        $this->setupClientMockPost(
            AuthorisedExaminerUrlBuilder::siteLink(self::ORG_ID),
            ['siteNumber' => self::SITE_NR],
            ['data' => $apiReturn]
        );

        $this->assertSame($apiReturn, $this->mapper->createSiteLink(self::ORG_ID, self::SITE_NR));
    }

    public function testChangeSiteLinkStatus()
    {
        $status = 'unit_Status';
        $apiReturn = 'apiReturn';

        $this->setupClientMockPut(
            AuthorisedExaminerUrlBuilder::siteLink(null, self::LINK_ID),
            $status,
            ['data' => $apiReturn]
        );

        $this->assertSame($apiReturn, $this->mapper->changeSiteLinkStatus(self::LINK_ID, $status));
    }

    public function testGetSiteLink()
    {
        $apiReturn = 'apiReturn';

        $this->setupClientMockGet(
            AuthorisedExaminerUrlBuilder::siteLink(null, self::LINK_ID),
            ['data' => $apiReturn]
        );

        $this->assertSame($apiReturn, $this->mapper->getSiteLink(self::LINK_ID));
    }
}
