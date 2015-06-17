<?php

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm7785RetrieveModelsOfMake
{
    /** @var string $makeCode */
    private $makeCode;
    /** @var array $models */
    private $models;
    /** @var string $testerUsername */
    private $testerUsername;
    /** @var string $makeName */
    private $makeName;
    /** @var string $modelName */
    private $modelName;

    public function __construct()
    {
        $this->testSupportHelper = new TestSupportHelper();
    }

    public function execute()
    {
        try {
            $result = TestShared::execCurlForJsonFromUrlBuilder(
                new \MotFitnesse\Util\CredentialsProvider($this->testerUsername, TestShared::PASSWORD),
                (new UrlBuilder())->vehicleDictionary()
                                  ->queryParam('searchType', 'model')
                                  ->queryParam('make', $this->makeCode)
                                  ->queryParam('searchTerm', $this->modelName)
            );

            if (!isset($result['data']) && isset($data['error'])) {
                $result['data'] = $result['error'] . ': ' . $result['content']['message'];
            }
        } catch (Exception $e) {
            $result['data'] = $e->getMessage();
        }

        $this->models = $result['data'];
    }

    public function setMakeCode($makeCode)
    {
        $this->makeCode = $makeCode;
    }

    public function setModelName($modelName)
    {
        $this->modelName = $modelName;
    }

    public function setTesterUsername($testerUsername)
    {
        $this->testerUsername = $testerUsername;
    }

    public function models()
    {
        return $this->models;
    }

    public function setMakeName()
    {
        return $this->makeName;
    }

}
