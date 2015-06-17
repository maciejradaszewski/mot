<?php

namespace Site\ViewModel;

use Core\ViewModel\Equipment\EquipmentViewModel;
use DvsaClient\Entity\ContactDetail;
use DvsaClient\Entity\SiteDailyOpeningHours;
use DvsaCommon\Dto\Equipment\EquipmentDto;
use DvsaCommon\Dto\MotTesting\MotTestInProgressDto;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Utility\ArrayUtils;
use Site\Authorization\VtsOverviewPagePermissions;
use Site\Model\SitePersonnel;
use Site\ViewModel\MotTest\MotTestInProgressViewModel;

/**
 * Class VTSDecorator
 *
 * @package Site\ViewModel
 */
class VTSDecorator implements \ArrayAccess
{
    const LIMIT_GREEN_THRESHOLD = 324.1;
    const LIMIT_AMBER_THRESHOLD = 459.2;

    private $data = [];
    /** @var  EquipmentViewModel[] */
    private $equipments;

    /** @var SiteDailyOpeningHours[] */
    private $openingHours;

    /**
     * @var SitePersonnel
     */
    private $personnel;

    /**
     * @var MotTestInProgressViewModel[]
     */
    private $testsInProgress;

    private $permissions;

    public function getPersonnel()
    {
        return $this->personnel;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param $input
     * @param $equipments      EquipmentDto[]
     * @param $testsInProgress MotTestInProgressDto[]
     * @param $permissions     VtsOverviewPagePermissions
     * @param $equipmentModelStatusMap
     */
    public function __construct(
        $input,
        $equipments,
        $testsInProgress,
        VtsOverviewPagePermissions $permissions,
        $equipmentModelStatusMap
    ) {
        $this->setupVtsDetails($input);
        $this->setupAeDetails($input);
        $this->setupOrganisationDetails($input);
        $this->setupCurrentTestingCapability($input);
        $this->setupPersonalRoles($input);
        $this->setupFacilities($input);
        $this->setupSiteAssessmentScore($input);
        $this->setupPositions($input);
        $this->setupContact($input);
        $this->setupOpeningHours($input);

        $this->setupEquipment($equipments, $equipmentModelStatusMap);

        $this->permissions = $permissions;

        $this->wrapTestsInProgress($testsInProgress);

        $this->data['defaultBrakeTestClass1And2'] = ArrayUtils::tryGet($input, 'defaultBrakeTestClass1And2');
        $this->data['defaultServiceBrakeTestClass3AndAbove'] = ArrayUtils::tryGet(
            $input,
            'defaultServiceBrakeTestClass3AndAbove'
        );
        $this->data['defaultParkingBrakeTestClass3AndAbove'] = ArrayUtils::tryGet(
            $input,
            'defaultParkingBrakeTestClass3AndAbove'
        );
    }

    /**
     * @param $equipmentDtos EquipmentDto[]
     * @param $equipmentModelStatusMap
     */
    private function setupEquipment($equipmentDtos, $equipmentModelStatusMap)
    {
        $this->equipments = ArrayUtils::map(
            $equipmentDtos,
            function (EquipmentDto $equipmentDto) use ($equipmentModelStatusMap) {
                $modelStatus = $equipmentModelStatusMap[$equipmentDto->getModel()->getStatus()];
                return new EquipmentViewModel($equipmentDto, $modelStatus);
            }
        );
    }

    private function setupOpeningHours($input)
    {
        $this->openingHours = ArrayUtils::tryGet($input, 'siteOpeningHours');
    }

    protected function setupVtsDetails($input)
    {
        if (isset($input['id'])) {
            $this->data['id'] = $input['id'];
        }
        if (isset($input['name'])) {
            $this->data['name'] = $input['name'];
        }
        if (isset($input['siteNumber'])) {
            $this->data['siteNumber'] = $input['siteNumber'];
        }
        if (isset($input['address'])) {
            $this->data['address'] = $input['address'];
        }
        if (isset($input['siteOpeningHours'])) {
            $this->data['siteOpeningHours'] = $input['siteOpeningHours'];
        }
    }

    protected function setupAeDetails($input)
    {
        if (!array_key_exists('authorisedExaminer', $input)) {
            return;
        }
        if (isset($input['authorisedExaminer']['id'])) {
            $this->data['authorisedExaminerId'] = $input['authorisedExaminer']['id'];
        }
        if (isset($input['authorisedExaminer']['id'])) {
            $this->data['aeNumber'] = $input['authorisedExaminer']['number'];
        }
        if (isset($input['authorisedExaminer']['slots'])) {
            $this->data['slots'] = $input['authorisedExaminer']['slots'];
        }
    }

    protected function setupOrganisationDetails($input)
    {
        if (!array_key_exists('organisation', $input)) {
            return;
        }
        if (isset($input['organisation']['id'])) {
            $this->data['organisationId'] = $input['organisation']['id'];
        }
        if (isset($input['organisation']['name'])) {
            $this->data['organisationName'] = $input['organisation']['name'];
        }
        if (isset($input['organisation']['slotBalance'])) {
            $this->data['organisationSlotBalance'] = $input['organisation']['slotBalance'];
        }
    }

    protected function setupCurrentTestingCapability($input)
    {
        if (isset($input['roles']) && is_array($input['roles'])) {
            $testClasses = $input['roles'];
            sort($testClasses);
            $this->data['testClasses'] = join(", ", $testClasses);
        }
    }

    protected function setupPersonalRoles($input)
    {
        if (isset($input['personalRoles']) && is_array($input['personalRoles'])) {
            $users = [];
            foreach ($input['personalRoles'] as $userData) {
                $displayRoles = [];
                foreach ($userData['roles'] as $roleData) {
                    if ('TESTER' === $roleData // TODO use \DvsaCommon\Enum\SiteRole values
                        || 'SM' === $roleData
                        || 'SA' === $roleData
                    ) {
                        $displayRoles[] = $roleData;
                    }
                }
                $userData['displayRoles'] = $displayRoles;
                $users[] = $userData;
            }
            $this->data['personalRoles'] = $users;
        }
    }

    protected function setupFacilities($input)
    {
        if (isset($input['facilities']) && is_array($input['facilities'])) {
            $this->data['facilities'] = $input['facilities'];
        }
    }

    protected function setupPositions($input)
    {
        $personnel = new SitePersonnel($input['positions']);
        $this->personnel = $personnel;
    }

    protected function setupContact($input)
    {
        $this->data['contact'] = ArrayUtils::firstOrNull(
            $input['contacts'],
            function (ContactDetail $contact) {
                return $contact->getType() === SiteContactTypeCode::BUSINESS;
            }
        );
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    protected function setupSiteAssessmentScore($input)
    {
        if (!isset($input['lastSiteAssessment']['siteAssessmentScore'])) {
            return;
        }

        $this->data['siteAssessmentScore'] = $input['lastSiteAssessment']['siteAssessmentScore'];

        if ($input['lastSiteAssessment']['siteAssessmentScore'] <= self::LIMIT_GREEN_THRESHOLD) {
            $this->data['siteAssessmentColour'] = 'green';
        } elseif ($input['lastSiteAssessment']['siteAssessmentScore'] <= self::LIMIT_AMBER_THRESHOLD) {
            $this->data['siteAssessmentColour'] = 'amber';
        } else {
            $this->data['siteAssessmentColour'] = 'red';
        }
    }

    public function getEquipments()
    {
        return $this->equipments;
    }


    /**
     * @return MotTest\MotTestInProgressViewModel[]
     */
    public function getTestsInProgress()
    {
        return $this->testsInProgress;
    }

    /**
     * @param MotTestInProgressDto $testsInProgress
     */
    private function wrapTestsInProgress($testsInProgress)
    {
        $wrappedTestsInProgress = [];
        foreach ($testsInProgress as $testInProgress){
            $wrappedTestsInProgress[] = new MotTestInProgressViewModel($testInProgress);
        }

        $this->testsInProgress = $wrappedTestsInProgress;
    }

}
