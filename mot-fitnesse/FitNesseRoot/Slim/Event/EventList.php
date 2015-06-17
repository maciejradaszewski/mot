<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\EventUrlBuilder;
use DvsaCommon\Dto\Event\EventFormDto;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommon\Dto\Common\DateDto;

/**
 * Class Event_EventList
 */
class Event_EventList
{
    public $username = TestShared::USERNAME_ENFORCEMENT;
    public $password = TestShared::PASSWORD;

    private $id;
    private $type;
    private $dateTo = null;
    private $dateFrom = null;
    private $isShowDate;
    private $search;
    private $displayStart;
    private $displayLength;
    private $sortCol;
    private $sortDir;

    private $apiResult;

    public function execute()
    {
        $dto = new EventFormDto();
        $dto->setIsShowDate($this->isShowDate)
            ->setDateFrom($this->dateFrom)
            ->setDateTo($this->dateTo)
            ->setSearch($this->search)
            ->setDisplayStart($this->displayStart)
            ->setDisplayLength($this->displayLength)
            ->setSortCol($this->sortCol)
            ->setSortDir($this->sortDir);

        $params = DtoHydrator::of()->extract($dto);
        $this->apiResult = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $this,
            EventUrlBuilder::of()->eventList($this->id, $this->type),
            $params
        );
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setIsShowDate($isShowDate)
    {
        $this->isShowDate = $isShowDate;
    }

    public function setDateFrom($date)
    {
        if (!empty($date)) {
            $date = explode('-', $date);
            $this->dateFrom = new DateDto($date[0], $date[1], $date[2]);
        }
    }

    public function setDateTo($date)
    {
        if (!empty($date)) {
            $date = explode('-', $date);
            $this->dateTo = new DateDto($date[0], $date[1], $date[2]);
        }
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }

    public function setDisplayStart($displayStart)
    {
        $this->displayStart = $displayStart;
    }

    public function setDisplayLength($displayLength)
    {
        $this->displayLength = $displayLength;
    }

    public function setSortCol($sortCol)
    {
        $this->sortCol = $sortCol;
    }

    public function setSortDir($sortDir)
    {
        $this->sortDir = $sortDir;
    }

    public function organisationId()
    {
        if (isset($this->apiResult['data'])) {
            return $this->apiResult['data']['organisationId'];
        }
    }

    public function siteId()
    {
        if (isset($this->apiResult['data'])) {
            return $this->apiResult['data']['siteId'];
        }
    }

    public function personId()
    {
        if (isset($this->apiResult['data'])) {
            return $this->apiResult['data']['personId'];
        }
    }

    public function events()
    {
        if (isset($this->apiResult['data'])) {
            return count($this->apiResult['data']['events']);
        }
    }

    public function error()
    {
        if (isset($this->apiResult['error'])) {
            return $this->apiResult['error'];
        }
    }
}
