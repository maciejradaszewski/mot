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
        return $this->getGroupAStatus() != null;
    }

    public function hasGroupBStatus()
    {
        return $this->getGroupBStatus() != null;
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
}
