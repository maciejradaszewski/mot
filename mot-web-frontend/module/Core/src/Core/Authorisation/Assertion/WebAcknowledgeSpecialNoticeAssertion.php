<?php

namespace Core\Authorisation\Assertion;

use DvsaCommon\Auth\Assertion\AcknowledgeSpecialNoticeAssertion;
use Application\Data\ApiPersonalDetails;

class WebAcknowledgeSpecialNoticeAssertion
{
    /**
     * @var AcknowledgeSpecialNoticeAssertion
     */
    private $assertion;

    /**
     * @var ApiPersonalDetails
     */
    private $apiPersonalDetails;

    /**
     * @param AcknowledgeSpecialNoticeAssertion $assertion
     * @param ApiPersonalDetails $apiPersonalDetails
     */
    public function __construct(AcknowledgeSpecialNoticeAssertion $assertion, ApiPersonalDetails $apiPersonalDetails)
    {
        $this->assertion = $assertion;
        $this->apiPersonalDetails = $apiPersonalDetails;
    }

    /**
     * @param int $personId
     * @return bool
     */
    public function isGranted($personId)
    {
        return $this->assertion->isGranted($this->getAuthorisation($personId));
    }

    /**
     * @param int $personId
     * @throws \DvsaCommon\Exception\UnauthorisedException
     */
    public function assertGranted($personId)
    {
        $this->assertion->assertGranted($this->getAuthorisation($personId));
    }

    /**
     * @param int $personId
     * @return array
     */
    private function getAuthorisation($personId)
    {
        return $this->apiPersonalDetails->getPersonalAuthorisationForMotTesting($personId);
    }
}
