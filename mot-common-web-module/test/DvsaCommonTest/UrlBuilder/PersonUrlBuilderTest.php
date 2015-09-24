<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AbstractUrlBuilder;
use DvsaCommon\UrlBuilder\PersonUrlBuilder;

/**
 * Tests for PersonUrlBuilder.
 */
class PersonUrlBuilderTest extends \PHPUnit_Framework_TestCase
{
    const USER_ID   = 99999;
    const USER_NAME = 'UNIT_USERNAME';

    /**
     * @dataProvider identifiersProvider
     */
    public function testByIdentifier($identifier)
    {
        $this->checkUrl(PersonUrlBuilder::byIdentifier($identifier), sprintf('person/username/%s', $identifier));
    }

    /**
     * @return array
     */
    public function identifiersProvider()
    {
        return [
            ['aaa-bbb'],
            ['tester1'],
            ['551ba8edd48dd8.33613431@example.com'],
            ['inactivetester_demo_not_req2'],
            ['vm-4499-tester-A@example.com'],
        ];
    }

    public function testById()
    {
        $base = 'person/' . self::USER_ID;

        $this->checkUrl(PersonUrlBuilder::byId(self::USER_ID), $base);
        $this->checkUrl(PersonUrlBuilder::byId(self::USER_ID)->authorisedExaminer(), $base . '/authorised-examiner');
        $this->checkUrl(PersonUrlBuilder::byId(self::USER_ID)->rbacRoles(), $base . '/rbac-roles');
        $this->checkUrl(
            PersonUrlBuilder::helpDeskProfile(self::USER_ID),
            $base . '/help-desk-profile-restricted'
        );
        $this->checkUrl(
            PersonUrlBuilder::helpDeskProfileUnrestricted(self::USER_ID),
            $base . '/help-desk-profile-unrestricted'
        );

        $this->checkUrl(PersonUrlBuilder::motTesting(self::USER_ID), $base . '/mot-testing');
        $this->checkUrl(PersonUrlBuilder::resetPin(self::USER_ID), $base . '/reset-pin');
        $this->checkUrl(PersonUrlBuilder::resetClaimAccount(self::USER_ID), $base . '/reset-claim-account');
    }

    public function testBySearchPerson()
    {
        $this->checkUrl(PersonUrlBuilder::personSearch(), 'search-person');
    }

    private function checkUrl(AbstractUrlBuilder $urlBuilder, $expectUrl)
    {
        $this->assertEquals($expectUrl, $urlBuilder->toString());
        $this->assertInstanceOf(PersonUrlBuilder::class, $urlBuilder);
    }
}
