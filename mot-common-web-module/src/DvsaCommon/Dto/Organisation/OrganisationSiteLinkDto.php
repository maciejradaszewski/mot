<?php

namespace DvsaCommon\Dto\Organisation;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\Site\SiteDto;

class OrganisationSiteLinkDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /**
     * @var SiteDto
     */
    private $site;
    /**
     * @var OrganisationDto
     */
    private $organisation;
    /**
     * @var string
     */
    private $status;
    /**
     * @var \DateTime
     */
    private $statusChangedOn;

    /**
     * @return SiteDto
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param SiteDto $site
     *
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return OrganisationDto
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @param OrganisationDto $organisation
     *
     * @return $this
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStatusChangedOn()
    {
        return $this->statusChangedOn;
    }

    /**
     * @param \DateTime $statusChangedOn
     *
     * @return $this
     */
    public function setStatusChangedOn($statusChangedOn)
    {
        $this->statusChangedOn = $statusChangedOn;
        return $this;
    }
}
