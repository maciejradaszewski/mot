<?php

namespace DvsaClient\Entity;

class TesterAuthorisation
{
    private $groupAStatus;
    private $groupBStatus;

    public function __construct(TesterGroupAuthorisationStatus $groupAStatus = null, TesterGroupAuthorisationStatus $groupBStatus = null)
    {
        $this->groupAStatus = $groupAStatus;
        $this->groupBStatus = $groupBStatus;
    }

    public function hasGroupAStatus()
    {
        return $this->hasGroupStatus($this->getGroupAStatus());
    }

    public function hasGroupBStatus()
    {
        return $this->hasGroupStatus($this->getGroupBStatus());
    }

    public function getGroupAStatus()
    {
        return $this->groupAStatus;
    }

    public function getGroupBStatus()
    {
        return $this->groupBStatus;
    }

    public function hasAnyTestingAuthorisation()
    {
        return $this->hasGroupAStatus() || $this->hasGroupBStatus();
    }

    private function hasGroupStatus(TesterGroupAuthorisationStatus $status = null)
    {
        return ($status != null && $status->getCode() != null);
    }
}
