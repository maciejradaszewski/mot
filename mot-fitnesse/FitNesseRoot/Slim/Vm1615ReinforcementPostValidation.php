<?php

require_once 'configure_autoload.php';
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm1615ReinforcementPostValidation
{
    public $username = TestShared::USERNAME_ENFORCEMENT;
    public $password = TestShared::PASSWORD;

    // values in
    private $title = '';
    private $id;
    private $score;
    private $notes;
    private $decision;
    private $category;
    private $motTestNumber;
    private $justification;
    private $errorExpected;
    private $messageExpected;
    private $expectedFailedItem;

    // values out
    private $result = null;

    protected function postData()
    {
        $postArray = [
            'reinspectionMotTest'      => 1234567892037,
            'motTest'                  => 432145678001,
            'mappedRfrs'               => [
                $this->id => [
                    "score"         => $this->score,
                    "decision"      => $this->decision,
                    "category"      => $this->category,
                    "justification" => $this->justification,
                    "error"         => $this->errorExpected
                ]
            ],
            'caseOutcome'              => '1',
            'record_assessment_button' => '',
            'finalJustification'       => ''
        ];

        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $this,
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
     * @return null
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return int
     */
    public function totalScore()
    {
        return 5;
    }

    /**
     * @param mixed $category
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $decision
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setDecision($decision)
    {
        $this->decision = $decision;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDecision()
    {
        return $this->decision;
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
     * @return mixed
     */
    public function getErrorExpected()
    {
        return $this->errorExpected;
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
     * @return mixed
     */
    public function getExpectedFailedItem()
    {
        return $this->expectedFailedItem;
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
     * @return mixed
     */
    public function getMessageExpected()
    {
        return $this->messageExpected;
    }

    /**
     * @param mixed $id
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $justification
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setJustification($justification)
    {
        $this->justification = $justification;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJustification()
    {
        return $this->justification;
    }

    /**
     * @param mixed $score
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param $notes
     *
     * @return $this
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @param int $motTestNumber
     *
     * @return Vm1615ReinforcementPostValidation
     */
    public function setTestNumber($motTestNumber)
    {
        $this->motTestNumber = $motTestNumber;

        return $this;
    }

    /**
     * @return int
     */
    public function getTestNumber()
    {
        return $this->motTestNumber;
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
                && isset($this->result['errorData']['mappedRfrs'][$this->motTestNumber])
                && isset($this->result['errorData']['mappedRfrs'][$this->motTestNumber][$this->expectedFailedItem])
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
