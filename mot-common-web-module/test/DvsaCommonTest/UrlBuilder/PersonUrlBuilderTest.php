<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AbstractUrlBuilder;
use DvsaCommon\UrlBuilder\PersonUrlBuilder;
use DvsaCommon\Validator\EmailAddressValidator;

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
            ['personurlbuildertest@' . EmailAddressValidator::TEST_DOMAIN],
            ['inactivetester_demo_not_req2'],
            ['personurlbuildertest@' . EmailAddressValidator::TEST_DOMAIN],
        ];
    }

    public function testById()
    {
        $base = PersonUrlBuilder::PERSON .'/'. self::USER_ID;

        $this->checkUrl(PersonUrlBuilder::byId(self::USER_ID), $base);
        $this->checkUrl(PersonUrlBuilder::byId(self::USER_ID)->authorisedExaminer(), $base . PersonUrlBuilder::AUTHORISED_EXAMINER);
        $this->checkUrl(PersonUrlBuilder::byId(self::USER_ID)->rbacRoles(), $base . PersonUrlBuilder::RBAC_ROLES);
        $this->checkUrl(
            PersonUrlBuilder::helpDeskProfile(self::USER_ID),
            $base . '/help-desk-profile-restricted'
        );
        $this->checkUrl(
            PersonUrlBuilder::helpDeskProfileUnrestricted(self::USER_ID),
            $base . '/help-desk-profile-unrestricted'
        );
        $this->checkUrl(PersonUrlBuilder::byId(self::USER_ID)->event(), $base . PersonUrlBuilder::EVENT);

        $this->checkUrl(PersonUrlBuilder::motTesting(self::USER_ID), $base . PersonUrlBuilder::MOT_TESTING);
        $this->checkUrl(PersonUrlBuilder::resetPin(self::USER_ID), $base . PersonUrlBuilder::RESET_PIN);
        $this->checkUrl(PersonUrlBuilder::resetClaimAccount(self::USER_ID), $base . PersonUrlBuilder::RESET_CLAIM_ACCOUNT);
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
