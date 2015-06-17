<?php

namespace OrganisationApi\Service;

use DvsaEntities\Repository\OrganisationRepository;

/**
 * Class AuthorisedExaminerSlotService
 */
class AuthorisedExaminerSlotService
{

    private $organisationRepository;

    public function __construct(
        OrganisationRepository $organisationRepository
    ) {
        $this->organisationRepository = $organisationRepository;
    }

    public function getSlotsForAuthorisedExaminer($id)
    {
        $organisation = $this->organisationRepository->getAuthorisedExaminer($id);

        return $organisation->getAuthorisedExaminer()->getSlots();
    }

    /**
     * Get slot usage for period or list of periods, where by period I mean number of days
     *
     * @param int
     * @param  int | array $period
     * @return int         | array
     */
    public function getSlotUsageForPeriod($organisationId, $period)
    {
    }

    public function incrementSlotsNumber($organisationId, $slotsNumber)
    {
    }

    public function decrementSlotsNumber($organisationId, $slot)
    {
    }

    public function getSlotsNumber($organisationId)
    {
    }
}
