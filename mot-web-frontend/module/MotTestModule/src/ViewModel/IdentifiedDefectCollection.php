<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link https://gitlab.motdev.org.uk/mot/mot
 */

namespace Dvsa\Mot\Frontend\MotTestModule\ViewModel;

use Dvsa\Mot\ApiClient\Resource\Item\MotTest;
use Dvsa\Mot\Frontend\MotTestModule\ViewModel\Exception\IdentifiedDefectNotFoundException;

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
     * Failures generated by a special process such as a brake test. These will
     * only ever be failures, there are no generated advisories or PRSes.
     *
     * @var IdentifiedDefect[]
     */
    private $generatedFailures;

    /**
     * IdentifiedDefectCollection constructor.
     *
     * @param IdentifiedDefect[] $failures
     * @param IdentifiedDefect[] $prs
     * @param IdentifiedDefect[] $advisories
     * @param IdentifiedDefect[] $generatedFailures
     */
    private function __construct(array $failures, array $prs, array $advisories, array $generatedFailures)
    {
        $this->failures = $failures;
        $this->prs = $prs;
        $this->advisories = $advisories;
        $this->generatedFailures = $generatedFailures;
    }

    /**
     * @param MotTest $motTestData
     *
     * @return IdentifiedDefectCollection
     */
    public static function fromMotApiData(MotTest $motTestData)
    {
        $failuresFromApi = isset($motTestData->getReasonsForRejection()->FAIL)
            ? $motTestData->getReasonsForRejection()->FAIL
            : [];

        $prsFromApi = isset($motTestData->getReasonsForRejection()->PRS)
            ? $motTestData->getReasonsForRejection()->PRS
            : [];

        $advisoriesFromApi = isset($motTestData->getReasonsForRejection()->ADVISORY)
            ? $motTestData->getReasonsForRejection()->ADVISORY
            : [];

        $failures = [];
        $prs = [];
        $advisories = [];
        $generatedFailures = [];

        foreach ($failuresFromApi as $failure) {
            $identifiedDefect = new IdentifiedDefect(
                self::FAILURE,
                $failure->locationLateral,
                $failure->locationLongitudinal,
                $failure->locationVertical,
                $failure->comment,
                $failure->failureDangerous,
                $failure->testItemSelectorDescription.' '.$failure->failureText,
                $failure->id,
                $failure->rfrId,
                $failure->onOriginalTest,
                $failure->generated,
                $failure->markedAsRepaired
            );

            if (isset($failure->generated) && $failure->generated) {
                array_push($generatedFailures, $identifiedDefect);
            } else {
                array_push($failures, $identifiedDefect);
            }
        }

        foreach ($prsFromApi as $loopPrs) {
            $identifiedDefect = new IdentifiedDefect(
                self::PRS,
                $loopPrs->locationLateral,
                $loopPrs->locationLongitudinal,
                $loopPrs->locationVertical,
                $loopPrs->comment,
                $loopPrs->failureDangerous,
                $loopPrs->testItemSelectorDescription.' '.$loopPrs->failureText,
                $loopPrs->id,
                $loopPrs->rfrId,
                $loopPrs->onOriginalTest,
                $loopPrs->generated,
                $loopPrs->markedAsRepaired
            );

            array_push($prs, $identifiedDefect);
        }

        foreach ($advisoriesFromApi as $advisory) {
            $defectName = array_key_exists('testItemSelectorDescription', $advisory)
                ? sprintf('%s ', $advisory->testItemSelectorDescription) : '';
            $defectName .= array_key_exists('failureText', $advisory) ? $advisory->failureText : '';

            $identifiedDefect = new IdentifiedDefect(
                self::ADVISORY,
                $advisory->locationLateral,
                $advisory->locationLongitudinal,
                $advisory->locationVertical,
                $advisory->comment,
                $advisory->failureDangerous,
                $defectName,
                $advisory->id,
                $advisory->rfrId,
                $advisory->onOriginalTest,
                $advisory->generated,
                $advisory->markedAsRepaired
            );

            array_push($advisories, $identifiedDefect);
        }

        return new self($failures, $prs, $advisories, $generatedFailures);
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
     * @return IdentifiedDefect[]
     */
    public function getGeneratedFailures()
    {
        return $this->generatedFailures;
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
    public function getNumberOfUnrepairedFailures()
    {
        $count = 0;
        foreach (array_keys($this->failures) as $k) {
            if (false === $this->failures[$k]->isMarkedAsRepaired()) {
                ++$count;
            }
        }

        return $count;
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
     * @return int
     */
    public function getNumberOfUnrepairedAdvisories()
    {
        $count = 0;
        foreach (array_keys($this->advisories) as $k) {
            if (false === $this->advisories[$k]->isMarkedAsRepaired()) {
                ++$count;
            }
        }

        return $count;
    }

    /**
     * @return int
     */
    public function getNumberOfGeneratedFailures()
    {
        return count($this->generatedFailures);
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
        $defect = array_filter($defects, function (IdentifiedDefect $e) use ($id) {
            return $e->getId() === $id;
        });

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
