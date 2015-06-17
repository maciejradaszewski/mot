<?php

require_once 'configure_autoload.php';
use MotFitnesse\Testing\Objects\MotTestCreate;
use MotFitnesse\Util\FtEnfTesterCredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm1615ReinforcementResultPostValidation
{
    // values in
    private $title = '';
    private $totalScore;
    private $notes;
    private $caseOutcome;
    private $finalJustification;
    private $errorExpected;
    private $messageExpected;
    private $expectedFailedItem;
    private $rfrs = [];

    private $motTestNumber;
    private $reinspectionMotTestNumber;
    private $rfrId;

    // values out
    private $result = null;

    public function beginTable()
    {
        $credentials = new FtEnfTesterCredentialsProvider();

        $vehicleTestHelper = new VehicleTestHelper(FitMotApiClient::createForCreds($credentials));
        $vehicleId = $vehicleTestHelper->generateVehicle();

        $motTestHelper = new MotTestHelper($credentials);
        $this->motTestNumber = $motTestHelper
            ->createPassedTest(
                (new MotTestCreate())
                    ->vehicleId($vehicleId)
                    ->siteId(2004)
            );

        $this->reinspectionMotTestNumber = $motTestHelper->createMotTest($vehicleId, null, 2004)['motTestNumber'];
        $this->rfrId = $motTestHelper->addRfr($this->reinspectionMotTestNumber);
        $motTestHelper->changeStatus($this->reinspectionMotTestNumber, 'ABORTED_VE', null, null, 'Because');
    }

    protected function postData()
    {
        $postArray = [
            'reinspectionMotTestNumber' => $this->reinspectionMotTestNumber,
            'motTestNumber'             => $this->motTestNumber,
            'mappedRfrs'                => $this->buildRfrs(),
            'caseOutcome'               => $this->caseOutcome,
            'record_assessment_button'  => '',
            'finalJustification'        => $this->finalJustification
        ];

        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            new FtEnfTesterCredentialsProvider(),
            (new UrlBuilder())->enforcementMotTestResult(),
            $postArray
        );
    }

    /**
     * @param string $title
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @param mixed $caseOutcome
     *
     * @return Vm1615ReinforcementResultPostValidation
     */
    public function setCaseOutcome($caseOutcome)
    {
        $this->caseOutcome = $caseOutcome;

        return $this;
    }

    /**
     * @param mixed $finalJustification
     *
     * @return Vm1615ReinforcementResultPostValidation
     */
    public function setFinalJustification($finalJustification)
    {
        $this->finalJustification = $finalJustification;

        return $this;
    }

    /**
     * @param null $result
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @param mixed $totalScore
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setTotalScore($totalScore)
    {
        $this->totalScore = $totalScore;

        return $this;
    }

    /**
     * @param mixed $errorExpected
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setErrorExpected($errorExpected)
    {
        $this->errorExpected = $errorExpected;

        return $this;
    }

    /**
     * @param mixed $expectedFailedItem
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setExpectedFailedItem($expectedFailedItem)
    {
        $this->expectedFailedItem = $expectedFailedItem;
        $this->postData();

        return $this;
    }

    /**
     * @param mixed $messageExpected
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setMessageExpected($messageExpected)
    {
        $this->messageExpected = $messageExpected;

        return $this;
    }

    /**
     * @param $notes
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @param int $testNumber
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setTestNumber($testNumber)
    {
        $this->testNumber = $testNumber;

        return $this;
    }

    /**
     * @param array $rfrs
     *
     * @return Vm1615ReinforcementResultPostValidation
     */
    public function setRfrs($rfrs)
    {
        $this->rfrs = $rfrs;

        return $this;
    }

    protected function buildRfrs()
    {
        $result = [];
        if (!empty($this->rfrs)) {
            // comma seperated line
            $lines = explode(',', $this->rfrs);
            //var_dump($lines);
            if (is_array($lines) && count($lines) > 0) {
                foreach ($lines as $line) {
                    $columns = explode('/', $line);
                    $rfr = [
                        'score'         => $columns[0],
                        'decision'      => $columns[1],
                        'category'      => $columns[2],
                        'justification' => $columns[3],
                        'error'         => $columns[4]
                    ];
                    $result[$this->rfrId] = $rfr;
                }
            }
        }

        return $result;
    }

    public function foundErrorExpected()
    {
        if ($this->errorExpected == 1) {

            // service errors can be thrown, so not actually checking a validator..
            if ($this->expectedFailedItem == null) {
                if ($this->result['errors'][0]['message'] == $this->messageExpected) {
                    return 'yes';
                } else {
                    return 'no';
                }
            }

            // check the validator values
            if (isset($this->result)
                && isset($this->result['errorData'])
                && isset($this->result['errorData'][$this->expectedFailedItem])
            ) {
                return 'yes';
            } else {
                return 'no';
            }
        }

        return 'n/a';
    }

    public function foundErrorMessageExpected()
    {
        if ($this->errorExpected == 1) {
            if (isset($this->result['errors'][0]['message'])
                && $this->result['errors'][0]['message'] == $this->messageExpected
            ) {
                return 'yes';
            } else {
                return 'no.. ' . $this->result['errors'][0]['message'];
            }
        } else {

            // if no error expected and one received, set to invalid
            if (isset($this->result['errors'][0]['message'])) {
                //var_dump($this->result);
                return 'unexpected error found.. ' . $this->result['errors'][0]['message'];
            }
        }

        return 'n/a';
    }
}
