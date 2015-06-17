<?php

require_once 'configure_autoload.php';
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm1612EnforcementCatalog
{
    private $catalogTitle = null;
    private $result = null;

    protected function getCatalogData()
    {
        $this->result = TestShared::execCurlForJsonFromUrlBuilder(
            new \MotFitnesse\Util\FtEnfTesterCredentialsProvider(),
            (new UrlBuilder())->dataCatalog()
        );
    }

    public function count()
    {
        if ($this->result == null) {
            $this->getCatalogData();
        }

        return count($this->result['data'][$this->catalogTitle]);
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function setCatalogTitle($value)
    {
        $this->catalogTitle = $value;
    }
}
