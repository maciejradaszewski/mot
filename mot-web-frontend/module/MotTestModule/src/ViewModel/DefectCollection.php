<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link https://gitlab.motdev.org.uk/mot/mot
 */

namespace Dvsa\Mot\Frontend\MotTestModule\ViewModel;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * A collection of Defect instances belonging to the same category.
 *
 * More specifically, these are defects that have not yet been added to a
 * vehicle. These are the defects that are displayed when you click on a
 * category in the browse journey that has no children categories.
 *
 * Previously known as Reasons For Rejection.
 */
class DefectCollection extends ArrayCollection
{
    /*
     * We don't want the testItemSelectorName for these, as it's just a
     * duplicate of the description.
     */
    const EMISSIONS_NOT_TESTED_CATEGORY_NAME = 'Emissions not tested';

    /*
     * Non-component advisories do not have inspection manual references
     * or advisory texts.
     */
    const NON_COMPONENT_ADVISORIES_CATEGORY_NAME = 'Non-component advisories';

    /**
     * DefectCollection constructor.
     *
     * @param Defect[] $defects
     */
    public function __construct(array $defects)
    {
        parent::__construct($defects);
    }

    /**
     * @param array $componentCategoriesFromApi
     *
     * @return DefectCollection
     */
    public static function fromDataFromApi(array $componentCategoriesFromApi)
    {
        $defectsFromApi = $componentCategoriesFromApi['reasonsForRejection'];

        $defects = [];

        foreach ($defectsFromApi as $defectFromApi) {
            $defect = new Defect(
                $defectFromApi['rfrId'],
                $defectFromApi['testItemSelectorId'],
                self::isDefectInEmissionsNotTestedCategory($componentCategoriesFromApi['testItemSelector']['name']) ?
                    $defectFromApi['testItemSelectorName'] : '',
                $defectFromApi['description'],
                !self::isDefectInNonComponentAdvisoriesCategory($componentCategoriesFromApi['testItemSelector']['name']) ?
                    $defectFromApi['advisoryText'] : '',
                !self::isDefectInNonComponentAdvisoriesCategory($componentCategoriesFromApi['testItemSelector']['name']) ?
                    $defectFromApi['inspectionManualReference'] : '',
                $defectFromApi['isAdvisory'],
                $defectFromApi['isPrsFail'],
                !$defectFromApi['isPrsFail'] && !$defectFromApi['isAdvisory']
            );

            array_push($defects, $defect);
        }

        return new self($defects);
    }

    /**
     * @return Defect[]
     */
    public function getDefects()
    {
        return $this->getValues();
    }

    /**
     * @param $categoryOfDefect
     *
     * @return bool
     */
    public static function isDefectInEmissionsNotTestedCategory($categoryOfDefect)
    {
        return $categoryOfDefect == self::EMISSIONS_NOT_TESTED_CATEGORY_NAME ? true : false;
    }

    /**
     * @param $categoryOfDefect
     *
     * @return bool
     */
    public static function isDefectInNonComponentAdvisoriesCategory($categoryOfDefect)
    {
        return $categoryOfDefect == self::NON_COMPONENT_ADVISORIES_CATEGORY_NAME ? true : false;
    }
}
