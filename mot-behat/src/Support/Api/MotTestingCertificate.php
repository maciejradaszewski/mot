<?php

namespace Dvsa\Mot\Behat\Support\Api;

use DvsaCommon\Enum\VehicleClassGroupCode;

class MotTestingCertificate extends MotApi
{
    const PATH = "person/{person_id}/mot-testing-certificate";

    public function createCertificate($token, $personId, array $data)
    {
        $path = str_replace("{person_id}", $personId, self::PATH);

        return $this->sendRequest(
            $token,
            MotApi::METHOD_POST,
            $path,
            $data
        );
    }

    public function updateCertificateForGroupA($token, $personId, array $data)
    {
        return $this->updateCertificate($token, $personId, VehicleClassGroupCode::BIKES, $data);
    }

    public function updateCertificateForGroupB($token, $personId, array $data)
    {
        return $this->updateCertificate($token, $personId, VehicleClassGroupCode::CARS_ETC, $data);
    }

    public function updateCertificate($token, $personId, $group, array $data)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_PUT,
            $this->getPathForGroup($personId, $group),
            $data
        );
    }

    public function removeCertificateForGroupA($token, $personId)
    {
        return $this->removeCertificate($token, $personId, VehicleClassGroupCode::BIKES);
    }

    public function removeCertificateForGroupB($token, $personId)
    {
        return $this->removeCertificate($token, $personId, VehicleClassGroupCode::CARS_ETC);
    }

    public function removeCertificate($token, $personId, $group)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_DELETE,
            $this->getPathForGroup($personId, $group)
        );
    }

    private function getPathForGroup($personId, $group)
    {
        $path = str_replace("{person_id}", $personId, self::PATH);
        return $path . "/" . strtolower($group);
    }
}
