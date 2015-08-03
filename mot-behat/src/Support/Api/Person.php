<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;

class Person extends MotApi
{
    const PATH = 'person/{user_id}';
    const PATH_PERSONAL_DETAILS = 'personal-details/{user_id}';
    const PATH_ROLES = '/roles';
    const PATH_DASHBOARD = '/dashboard';
    const PATH_RBAC_ROLES = '/rbac-roles';

    public function getPersonMotTestingClasses($token, $user_id)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_GET,
            str_replace('{user_id}', $user_id, self::PATH).'/mot-testing'
        );
    }

    /**
     * @param string $token
     * @param int $user_id
     * @return \Dvsa\Mot\Behat\Support\Response
     */
    public function getPersonDashboard($token, $user_id)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_GET,
            str_replace('{user_id}', $user_id, self::PATH).self::PATH_DASHBOARD
        );
    }

    /**
     * @param string $token
     * @param int $user_id
     * @return \Dvsa\Mot\Behat\Support\Response
     */
    public function getPersonRBAC($token, $user_id)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_GET,
            str_replace('{user_id}', $user_id, self::PATH).self::PATH_RBAC_ROLES
        );
    }

    public function getPersonDetails($token, $user_id)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_GET,
            str_replace('{user_id}', $user_id, self::PATH_PERSONAL_DETAILS)
        );
    }

    /**
     * @param string $token
     * @param int $user_id
     * @param string $roleCode
     * @return \Dvsa\Mot\Behat\Support\Response
     */
    public function addPersonRole($token, $user_id, $roleCode)
    {
        $jsonData = [
            'personSystemRoleCode' => $roleCode
        ];
        return $this->sendRequest(
            $token,
            MotApi::METHOD_POST,
            str_replace('{user_id}', $user_id, self::PATH.self::PATH_ROLES),
            $jsonData
        );
    }

    public function updateUserEmail($token, $user_id, $newEmail, $emailConfirmation = null)
    {
        if (is_null($emailConfirmation)) {
            $emailConfirmation = $newEmail;
        }

        $body = [
            'title' => 'Mr',
            'firstName' => 'Bob',
            'middleName' => 'Thomas',
            'surname' => 'Arctor',
            'gender' => 'Male',
            'drivingLicenceNumber' => 'GARDN605109C99LY60',
            'drivingLicenceRegion' => 'GB',
            'addressLine1' => 'Straw Hut',
            'addressLine2' => '5 Uncanny St',
            'addressLine3' => '',
            'town' => 'Liverpool',
            'postcode' => 'L1 1PQ',
            'email' => $newEmail,
            'emailConfirmation' => $emailConfirmation,
            'phoneNumber' => '+768-45-4433630',
            'update-profile' => 'update-profile',
            'dateOfBirth' => '1981-04-24',
        ];

        return $this->sendRequest(
            $token,
            MotApi::METHOD_PUT,
            str_replace('{user_id}', $user_id, self::PATH_PERSONAL_DETAILS),
            $body
        );
    }
}
