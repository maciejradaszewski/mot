<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Checks api for contact details of a particular site
 */
class Vm2227SiteContactDetails
{
    private $username = TestShared::USERNAME_ENFORCEMENT;
    private $password = TestShared::PASSWORD;

    private $result;
    private $siteId;
    /** @var  \DvsaCommon\Dto\Contact\ContactDto */
    private $contact;

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    public function success()
    {
        $curlHandle = $this->getCurlHandle();
        TestShared::SetupCurlOptions($curlHandle);
        TestShared::setAuthorizationInHeaderForUser($this->username, $this->password, $curlHandle);
        $this->result = TestShared::execCurlForJson($curlHandle);

        $this->contact = null;
        if (TestShared::resultIsSuccess($this->result)) {
            /** @var \DvsaCommon\Dto\Site\VehicleTestingStationDto $dto */
            $dto = \DvsaCommon\Utility\DtoHydrator::jsonToDto($this->result['data']);
            $this->contact = $dto->getContactByType(\DvsaCommon\Enum\SiteContactTypeCode::BUSINESS);
        }
        return TestShared::resultIsSuccess($this->result);
    }

    private function getCurlHandle()
    {
        return curl_init(
            (new UrlBuilder())
                ->vehicleTestingStation()
                ->routeParam('id', $this->siteId)
                ->toString()
        );
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }

    public function addressLine1()
    {
        if ($this->contact !== null) {
            return $this->contact->getAddress()->getAddressLine1();
        }
    }

    public function addressLine2()
    {
        if ($this->contact !== null) {
            return $this->contact->getAddress()->getAddressLine2();
        }
    }

    public function addressLine3()
    {
        if ($this->contact !== null) {
            return $this->contact->getAddress()->getAddressLine3();
        }
    }

    public function town()
    {
        if ($this->contact !== null) {
            return $this->contact->getAddress()->getTown();
        }
    }

    public function postcode()
    {
        if ($this->contact !== null) {
            return $this->contact->getAddress()->getPostcode();
        }
    }

    public function phoneNumber()
    {
        if ($this->contact !== null) {
            return $this->contact->getPrimaryPhoneNumber();
        }
    }

}
