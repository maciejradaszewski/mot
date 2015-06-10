<?php

namespace UserAdmin\Service;

use Application\Service\CatalogService;
use DvsaClient\Mapper\TesterQualificationStatusMapper;
use DvsaCommon\Enum\VehicleClassCode;
use DvsaCommon\Enum\VehicleClassGroupCode;
use DvsaCommon\Model\VehicleClassGroup;
use DvsaCommon\Utility\ArrayUtils;

class TesterQualificationStatusService
{
    const DEFAULT_NO_STATUS = 'Not Applied';

    const ERR_MSG_DIFFERENT_CLASSES_IN_GROUP = 'All classes in the group %s should have similar status';
    const ERR_MSG_UNEXPECTED_CLASSES = 'Unexpected Vehicle class (%s) spotted';

    /** @var  array */
    private $qualifications;

    /** @var TesterQualificationStatusMapper */
    private $testerQualificationStatusMapper;

    /** @var CatalogService */
    private $catalogService;

    /**
     * @param TesterQualificationStatusMapper $testerQualificationStatusMapper
     * @param CatalogService $catalogService
     */
    public function __construct(
        TesterQualificationStatusMapper $testerQualificationStatusMapper,
        CatalogService $catalogService
    ) {
        $this->testerQualificationStatusMapper = $testerQualificationStatusMapper;
        $this->catalogService = $catalogService;
    }

    /**
     * @param int $personId
     * @return array
     */
    public function getPersonGroupQualificationStatus($personId)
    {
        $qualifications = $this->getPersonQualificationStatus($personId);
        $groupedQualifications = $this->groupQualificationStatus($qualifications);
        return $groupedQualifications;
    }

    /**
     * @param int $personId
     * @return array
     */
    public function getPersonQualificationStatus($personId)
    {
        if (is_null($this->qualifications)) {
            $this->qualifications = $this->testerQualificationStatusMapper->getTesterQualificationStatus($personId)['data'];
        }

        return $this->qualifications;
    }

    /**
     * @param array $qualifications
     * @throws \DomainException
     */
    private function groupQualificationStatus($qualifications)
    {
        $groupQualification = [
            VehicleClassGroupCode::BIKES => [],
            VehicleClassGroupCode::CARS_ETC => []
        ];

        foreach ($qualifications as $class => $status) {
            $groupQualification[$this->getClassGroup($class)][] = $status;
        }

        $this->validateAndSquashGroupStatus($groupQualification);

        return $groupQualification;
    }

    /**
     * @param string $vehicleClass e.g. class1, class2, ..., class7
     * @return string e.g. A or B
     * @throws \UnexpectedValueException
     */
    private function getClassGroup($vehicleClass)
    {
        $group = '';

        if (VehicleClassGroup::isGroupA($vehicleClass)) {
            $group = VehicleClassGroupCode::BIKES;
        } elseif (VehicleClassGroup::isGroupB($vehicleClass)) {
            $group = VehicleClassGroupCode::CARS_ETC;
        }

        if (VehicleClassGroupCode::BIKES !== $group && VehicleClassGroupCode::CARS_ETC !== $group) {
            throw new \UnexpectedValueException(sprintf(self::ERR_MSG_UNEXPECTED_CLASSES, $vehicleClass));
        }

        return $group;
    }

    /**
     * @param $groupQualification
     * @throws \DomainException
     */
    private function validateAndSquashGroupStatus(&$groupQualification)
    {

        /**
         * @var string $group e.g. A or B
         * @var array $status e.g. ["QLFD", "QLFD"]
         */
        foreach ($groupQualification as $group => &$status) {

            if (!ArrayUtils::containsOnlyNull($status)) {

                $avc = array_unique($status);
                $numberOfDifferentStatus = count($avc);

                if (1 !== $numberOfDifferentStatus) {
                    throw new \DomainException(sprintf(self::ERR_MSG_DIFFERENT_CLASSES_IN_GROUP, $group));
                }

            }

            $status = $this->convertStatusCodeArrayToName($status);
        }

    }

    /**
     * @param int $personId
     * @return bool
     */
    public function doesPersonHasAnyQualification($personId)
    {
        $qualifications = $this->getPersonQualificationStatus($personId);

        return !ArrayUtils::containsOnlyNull($qualifications);
    }

    /**
     * Converts array of qualification status (e.g. ["QLFD", "QLFD"]) into status name such as "Qualification"
     * If qualification status are null then use the 'Not Applied' instead
     * @param array $status
     * @return string e.g.
     */
    private function convertStatusCodeArrayToName($status)
    {
        $qualificationsStatusNames = $this->catalogService->getQualificationStatus();

        if (isset($qualificationsStatusNames[array_unique($status)[0]])) {
            $status = $qualificationsStatusNames[array_unique($status)[0]];
        } else {
            $status = self::DEFAULT_NO_STATUS;
        }

        return $status;
    }


    /**
     * Receives group name ('A' or 'B') and returns respective classes
     * e.g.
     *     'Class 1, Class 2' or 'Class 3, Class 4, Class 5, Class 7'
     *
     * @param $group e.g. 'A' or 'B'
     * @return string e.g. Class 1, Class 2
     */
    public static function getGroupPrintableClasses($group)
    {
        $method = sprintf('getGroup%sClasses', $group);
        $classes = VehicleClassGroup::$method();

        $printable = array_map(
            function ($item) {
                return ucfirst(
                    str_replace(
                        VehicleClassGroup::CLASS_PREFIX,
                        VehicleClassGroup::CLASS_PREFIX . ' ',
                        $item
                    )
                );
            },
            $classes
        );

        return implode(', ', $printable);
    }
}
