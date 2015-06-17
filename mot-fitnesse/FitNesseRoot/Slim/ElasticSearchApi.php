<?php

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use DvsaElasticSearch\Controller\ESController;

class ElasticSearchApi
{
    const LOCKFILE = '/tmp/esprocess.lock';

    public $username = 'ft-enf-tester';
    public $password = TestShared::PASSWORD;
    protected $fullStatus;
    protected $rebuildStatus;
    protected $rebuildMode;
    protected $passphrase;

    public function resetLock()
    {
        $urlBuilder = (new UrlBuilder())->elasticSearchUnlock();

        $response = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $this,
            $urlBuilder,
            ['token' => '227ac6d2d25a8b6d81b6ef86fca1c8877e33b18a']
        );

        if (isset($response['data'])) {
            return $response['data'];
        }
        return false;
    }

    public function requestFullStatus()
    {
        $url = (new UrlBuilder())->elasticSearchStatus();
        $this->fullStatus = TestShared::get($url->toString(), $this->username, $this->password);
        return $this->fullStatus;
    }

    public function requestRebuildStatus()
    {
        $url = (new UrlBuilder())->elasticSearchStatusRebuild();
        $this->rebuildStatus = TestShared::get($url->toString(), $this->username, $this->password);

        return !is_null($this->rebuildStatus);
    }

    public function rebuildModeReason()
    {
        if (isset($this->rebuildStatus['status']['rebuild'])) {
            return $this->rebuildStatus['status']['rebuild'];
        }
        return false;
    }

    public function setRebuildMode($mode)
    {
        $this->rebuildMode = $mode;
    }

    public function rebuildResult()
    {
        $urlBuilder = (new UrlBuilder())->elasticSearchRebuild()
            ->routeParam('type', $this->rebuildMode);

        $this->rebuildStatus = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $this,
            $urlBuilder,
            ['token' => $this->passphrase]
        );

        if (isset($this->rebuildStatus['errors'])) {
            return false;
        }
        return true;
    }

    public function failureText()
    {
        if (isset($this->rebuildStatus['errors'])) {
            return $this->rebuildStatus['errors']['status'];
        }
        return false;
    }

    public function setRebuildPassphrase($phrase)
    {
        $this->passphrase = $phrase;
    }

    public function rebuildReason()
    {
        if ($this->rebuildStatus['data']['reason']) {
            return $this->rebuildStatus['data']['reason'];
        }
        return null;
    }

    public function rebuildState()
    {
        if ($this->rebuildStatus['data']['state']) {
            return $this->rebuildStatus['data']['state'];
        }
        return null;
    }
}
