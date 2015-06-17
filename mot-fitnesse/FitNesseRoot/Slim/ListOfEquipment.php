<?php

use MotFitnesse\Util\Tester1CredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class ListOfEquipment
{
    private $result;

    public $username = 'schememgt';
    public $password = TestShared::PASSWORD;

    public function execute()
    {
        $urlBuilder = UrlBuilder::equipmentModel();

        $this->result = TestShared::execCurlForJsonFromUrlBuilder($this, $urlBuilder);
    }

    public function isEquipmentNameLengthLessOrEqual50()
    {
        return $this->isFieldLengthLessOrEqual('typeName', 50);
    }

    public function isEquipmentCodeLengthLessOrEqual5()
    {
        return $this->isFieldLengthLessOrEqual('code', 5);
    }

    public function isMakeLengthLessOrEqual100()
    {
        return $this->isFieldLengthLessOrEqual('makeName', 100);
    }

    public function isModelLengthLessOrEqual100()
    {
        return $this->isFieldLengthLessOrEqual('name', 100);
    }

    private function isFieldLengthLessOrEqual($fieldName, $length)
    {
        foreach ($this->result['data'] as $row) {
            if (strlen($row[$fieldName]) > $length) {
                return 'No';
            }
        }

        return 'Yes';
    }
}
