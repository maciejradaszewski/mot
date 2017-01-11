<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link https://gitlab.motdev.org.uk/mot/mot
 */

namespace Dvsa\Mot\Frontend\MotTestModule\ViewModel;

use Dvsa\Mot\Frontend\MotTestModule\ViewModel\Exception\IdentifiedDefectNotFoundException;
use DvsaCommon\Dto\Common\MotTestDto;

/**
 * Contains the information required for the list of identified defects in an MOT
 * test.
 *
 * An identified defect is a defect that has been identified on a vehicle in the
 * process of a tester carrying out an MOT test, as opposed to a Defect, which
 * is not associated with a vehicle.
 *
 * @see IdentifiedDefect
 */
class IdentifiedDefectCollection
{
    const FAILURE = 'failure';
    const PRS = 'PRS';
    const ADVISORY = 'advisory';

    const FAILURE_KEY = 'FAIL';
    const PRS_KEY = 'PRS';
    const ADVISORY_KEY = 'ADVISORY';

    /**
     * @var IdentifiedDefect[]
     */
    private $failures;

    /**
     * @var IdentifiedDefect[]
     */
    private $prs;

    /**
     * @var IdentifiedDefect[]
     */
    private $advisories;

    /**
     * IdentifiedDefectCollection constructor.
     *
     * @param IdentifiedDefect[] $failures
     * @param IdentifiedDefect[] $prs
     * @param IdentifiedDefect[] $advisories
     */
    private function __construct(array $failures, array $prs, array $advisories)
    {
        $this->failures = $failures;
        $this->prs = $prs;
        $this->advisories = $advisories;
    }

    /**
     * @param MotTestDto $motTestData
     *
     * @return IdentifiedDefectCollection
     */
    public static function fromMotApiData(MotTestDto $motTestData)
    {
        $failuresFromApi = isset($motTestData->getReasonsForRejection()[self::FAILURE_KEY])
            ? $motTestData->getReasonsForRejection()[self::FAILURE_KEY]
            : [];

        $prsFromApi = isset($motTestData->getReasonsForRejection()[self::PRS_KEY])
            ? $motTestData->getReasonsForRejection()[self::PRS_KEY]
            : [];

        $advisoriesFromApi = isset($motTestData->getReasonsForRejection()[self::ADVISORY_KEY])
            ? $motTestData->getReasonsForRejection()[self::ADVISORY_KEY]
            : [];

        $failures = [];
        $prs = [];
        $advisories = [];

        foreach ($failuresFromApi as $failure) {
            $identifiedDefect = new IdentifiedDefect(
                self::FAILURE,
                $failure['locationLateral'],
                $failure['locationLongitudinal'],
                $failure['locationVertical'],
                $failure['comment'],
                $failure['failureDangerous'],
                $failure['testItemSelectorDescription'] . ' ' . $failure['failureText'],
                $failure['id'],
                $failure['rfrId'],
                $failure['onOriginalTest']
            );

            array_push($failures, $identifiedDefect);
        }

        foreach ($prsFromApi as $loopPrs) {
            $identifiedDefect = new IdentifiedDefect(
                self::PRS,
                $loopPrs['locationLateral'],
                $loopPrs['locationLongitudinal'],
                $loopPrs['locationVertical'],
                $loopPrs['comment'],
                $loopPrs['failureDangerous'],
                $loopPrs['testItemSelectorDescription'] . ' ' . $loopPrs['failureText'],
                $loopPrs['id'],
                $loopPrs['rfrId'],
                $loopPrs['onOriginalTest']
            );

            array_push($prs, $identifiedDefect);
        }

        foreach ($advisoriesFromApi as $advisory) {
            $defectName = array_key_exists('testItemSelectorDescription', $advisory)
                ? sprintf('%s ', $advisory['testItemSelectorDescription']) : '';
            $defectName .= array_key_exists('failureText', $advisory) ? $advisory['failureText'] : '';
            $defectName = trim($defectName);

            $identifiedDefect = new IdentifiedDefect(
                self::ADVISORY,
                $advisory['locationLateral'],
                $advisory['locationLongitudinal'],
                $advisory['locationVertical'],
                $advisory['comment'],
                $advisory['failureDangerous'],
                $defectName,
                $advisory['id'],
                $advisory['rfrId'],
                $advisory['onOriginalTest']
            );

            array_push($advisories, $identifiedDefect);
        }

        return new self($failures, $prs, $advisories);
    }

    /**
     * @return IdentifiedDefect[]
     */
    public function getFailures()
    {
        return $this->failures;
    }

    /**
     * @return IdentifiedDefect[]
     */
    public function getPrs()
    {
        return $this->prs;
    }

    /**
     * @return IdentifiedDefect[]
     */
    public function getAdvisories()
    {
        return $this->advisories;
    }

    /**
     * @return int
     */
    public function getNumberOfFailures()
    {
        return count($this->failures);
    }

    /**
     * @return int
     */
    public function getNumberOfPrs()
    {
        return count($this->prs);
    }

    /**
     * @return int
     */
    public function getNumberOfAdvisories()
    {
        return count($this->advisories);
    }

    /**
     * @return bool
     */
    public function hasFailuresPrsOrAdvisories()
    {
        return !(empty($this->failures) && empty($this->prs) && empty($this->advisories));
    }

    /**
     * Get a unique defect associated with a test. N.B. this is the row id
     * which uniquely associates an IdentifiedDefect with a test. This is NOT
     * the id of a Defect (i.e., a potential defect, or one that is yet to
     * be added to a test.
     *
     * @param int $id
     *
     * @return IdentifiedDefect
     *
     * @see IdentifiedDefect
     * @see Defect
     */
    public function getDefectById($id)
    {
        $defects = array_merge($this->failures, $this->prs, $this->advisories);

        /*
         * This is only ever going to return one value as we're comparing
         * the primary keys of elements.
         */
        $defect = array_filter($defects, function (IdentifiedDefect $e) use ($id) {return $e->getId() === $id;});

        if (empty($defect)) {
            throw new IdentifiedDefectNotFoundException($id);
        }

        /*
         * We've messed with the keys, so calling array_merge rebases the
         * array keys on zero. Since there's only ever going to be one element
         * in the array we just grab the first one.
         */
        $defect = array_merge($defect)[0];

        return $defect;
    }
}
