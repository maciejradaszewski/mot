<?php

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\AuthorisedExaminerUrlBuilder;

class ManageAuthorisedExaminerPrincipal
{
    const MSG_SUCCESS = 'true';

    private $username;
    private $aeId;

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setAeId($aeId)
    {
        $this->aeId = $aeId;
    }

    public function canCreate()
    {
        $result = $this->create($this->username);
        return $this->getMessage($result);
    }

    private function create($createdBy)
    {
        $urlBuilder = AuthorisedExaminerUrlBuilder::authorisedExaminer($this->aeId)->authorisedExaminerPrincipal();

        return TestShared::execCurlFormPostForJsonFromUrlBuilder(
            (new \MotFitnesse\Util\CredentialsProvider($createdBy, TestShared::PASSWORD)),
            $urlBuilder,
            $this->prepareDataForCreate()
        );
    }

    private function prepareDataForCreate()
    {
        return [
            "firstName" => "Firstname",
            "middleName" => "Middlename",
            "surname" => "surname",
            "addressLine1" => "Address Line 1",
            "addressLine2" => "Address Line 2",
            "addressLine3" => "Address Line 3",
            "town" => "Town or city",
            "postcode" => "Postcode",
            "email" => "aep@email.com",
            "phoneNumber" => "333444555",
            "convictions" => true,
        ];
    }

    public function canRemove()
    {
        $result = $this->remove();
        return $this->getMessage($result);
    }

    public function remove()
    {
        $aepId = $this->prepareDataForDelete();
        $urlBuilder = AuthorisedExaminerUrlBuilder::authorisedExaminer($this->aeId)
            ->authorisedExaminerPrincipal()
            ->routeParam('principalId', $aepId);

        return TestShared::execCurlFormDeleteForJsonFromUrlBuilder(
            (new \MotFitnesse\Util\CredentialsProvider($this->username, TestShared::PASSWORD)),
            $urlBuilder
        );
    }

    private function prepareDataForDelete()
    {
        $result = $this->create(TestShared::USERNAME_SCHEMEMGT);
        $msg = $this->getMessage($result);

        if ($msg === self::MSG_SUCCESS) {
            return $result['data']['authorisedExaminerPrincipalId'];
        }

        return 0;
    }

    private function getMessage($result)
    {
        if (TestShared::resultIsSuccess($result)) {
            return self::MSG_SUCCESS;
        }

        return TestShared::errorMessages($result);
    }
}
