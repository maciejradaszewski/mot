<?php

namespace Dvsa\Mot\Behat\Support\Api;

class Person extends MotApi
{
    const PATH = 'person/{user_id}';
    const PATH_PERSONAL_DETAILS = 'personal-details/{user_id}';
    const PATH_ROLES = '/roles';
    const PATH_ROLES_ROLE = '/roles/{role}';
    const PATH_DASHBOARD = '/dashboard';
    const PATH_STATS = '/stats';
    const PATH_RBAC_ROLES = '/rbac-roles';
    const PATH_PASSWORD = '/password';
    const PATH_NAME = '/name';
    const PATH_DATE_OF_BIRTH = '/date-of-birth';
    const PATH_LICENCE_UPDATE = '/driving-licence';
    const PATH_TELEPHONE_NUMBER = '/phone-number';

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
    public function getPersonStats($token, $user_id)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_GET,
            str_replace('{user_id}', $user_id, self::PATH).self::PATH_STATS
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

    /**
     * @param $token
     * @param $user_id
     * @param $roleCode
     * @return \Dvsa\Mot\Behat\Support\Response
     */
    public function removePersonRole($token, $user_id, $roleCode)
    {
        $uri = str_replace(['{user_id}', '{role}'], [$user_id, $roleCode], self::PATH . self::PATH_ROLES_ROLE);
        return $this->sendRequest(
            $token,
            MotApi::METHOD_DELETE,
            $uri,
            []
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

    public function addLicence($token, $userId, $licenceData)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_GET,
            str_replace('{user_id}', $userId, self::PATH.self::PATH_LICENCE_UPDATE),
            $licenceData
        );
    }

    public function changePassword($token, $user_id, array $data)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_PUT,
            str_replace('{user_id}', $user_id, self::PATH.self::PATH_PASSWORD),
            $data
        );
    }

    public function changeName($token, $user_id, array$data)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_POST,
            str_replace('{user_id}', $user_id, self::PATH.self::PATH_NAME),
            $data
        );
    }

    public function changeDateOfBirth($token, $userId, array $data)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_POST,
            str_replace('{user_id}', $userId, self::PATH.self::PATH_DATE_OF_BIRTH),
            $data
        );
    }

    /**
     * @param string      $token
     * @param int|string  $userId
     * @param array       $telephoneData
     * @return \Dvsa\Mot\Behat\Support\Response
     */
    public function changeTelephoneNumber($token, $userId, array $telephoneData)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_PUT,
            str_replace('{user_id}', $userId, self::PATH.self::PATH_TELEPHONE_NUMBER),
            $telephoneData
        );
    }
}
