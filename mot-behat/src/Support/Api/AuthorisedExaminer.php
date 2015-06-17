<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;
use DvsaCommon\Constants\OrganisationType;

class AuthorisedExaminer extends MotApi
{
    const PATH_GET = 'authorised-examiner/{user_id}';
    const PATH_SEARCH = "authorised-examiner/number?number={ae_number}";
    const PATH_CREATE_AE = "authorised-examiner";
    const PATH_DELETE_AE = "organisation/2/position/4";

    public function search($token, $aeNumber)
    {
        return $this->client->request(new Request(
            'GET',
            str_replace('{ae_number}', $aeNumber, self::PATH_SEARCH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token]
        ));
    }

    public function getAEDetails($token, $userId)
    {
        return $this->client->request(new Request(
            'GET',
            str_replace('{user_id}', $userId, self::PATH_GET),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token]
        ));
    }

    public function createAE($token, $companyType)
    {
        $body = json_encode([
            'organisationName' => 'some ae name',
            'tradingAs' => 'its optional',
            'companyType' => $companyType,
            'organisationType' => OrganisationType::DVSA,
            'registeredCompanyNumber' => '3211230',
            'addressLine1' => '1 West Street',
            'addressLine2' => 'Bedminster',
            'addressLine3' => 'Bristol',
            'town' => 'Bristol',
            'postcode' => 'bs33nn',
            'email' => 'some@aename.com',
            'emailConfirmation' => 'some@aename.com',
            'phoneNumber' => '01179082928',
            'faxNumber' => '',
            'correspondenceContactDetailsSame' => '1',
            'correspondenceAddressLine1' => '',
            'correspondenceAddressLine2' => '',
            'correspondenceAddressLine3' => '',
            'correspondenceTown' => '',
            'correspondencePostcode' => '',
            'correspondenceEmail' => '',
            'correspondenceEmailConfirmation' => '',
            'correspondencePhoneNumber' => '',
            'correspondenceFaxNumber' => '',
        ]);

        return $this->client->request(new Request(
            'POST',
            self::PATH_CREATE_AE,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }

    public function removeAE($token){
        return $this->client->request(new Request(
            'DELETE',
            self::PATH_DELETE_AE,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token]
        ));
    }
}