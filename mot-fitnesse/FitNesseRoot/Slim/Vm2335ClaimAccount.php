<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\UrlBuilder;

class Vm2335ClaimAccount
{
    private $result;
    private $createdUsers = [];
    /** @var TestSupportHelper */
    private $testSupportHelper;
    private $schmMgrUsername;
    private $vtsId;
    private $data = [];
    private $securityQuestionsOne = [];
    private $securityQuestionsTwo = [];

    private $user;
    private $accountClaimRequired = false;

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setAccountClaimRequired($required)
    {
        if (trim($required) === "true") {
            $this->accountClaimRequired = true;
        }
    }

    public function setEmailRequired($required)
    {
        $this->data['emailOptOut'] = false;
        if (trim($required) === "false") {
            $this->data['emailOptOut'] = true;
        }
    }

    public function setEmail($email)
    {
        $this->data['email'] = $email;
    }

    public function setEmailConfirmation($emailConfirmation)
    {
        $this->data['emailConfirmation'] = $emailConfirmation;
    }

    public function setPassword($password)
    {
        $this->data['password'] = $password;
    }

    public function setPasswordConfirmation($passwordConfirmation)
    {
        $this->data['passwordConfirmation'] = $passwordConfirmation;
    }

    public function setPin($pin)
    {
        $this->data['pin'] = $pin;
    }

    public function setSecurityQuestionOne($question)
    {
        $this->data["securityQuestionOneId"] = null;
        foreach ($this->securityQuestionsOne as $securityQuestion) {
            if ($securityQuestion['text'] === trim($question)) {
                $this->data["securityQuestionOneId"] = $securityQuestion["id"];
                break;
            }
        }
    }

    public function setSecurityAnswerOne($secretAnswerOne)
    {
        $this->data['securityAnswerOne'] = $secretAnswerOne;
    }

    public function setSecurityQuestionTwo($question)
    {
        $this->data["securityQuestionTwoId"] = 666;
        foreach ($this->securityQuestionsTwo as $securityQuestion) {
            if ($securityQuestion['text'] === trim($question)) {
                $this->data["securityQuestionTwoId"] = $securityQuestion["id"];
                break;
            }
        }
    }

    public function setSecurityAnswerTwo($secretAnswerTwo)
    {
        $this->data['securityAnswerTwo'] = $secretAnswerTwo;
    }

    public function beginTable()
    {
        $this->createFixtures();
    }

    public function execute()
    {
        $tester = $this->createTester($this->user, $this->accountClaimRequired);

        $this->data['personId'] = $tester['personId'];
        $urlBuilder = (new UrlBuilder())->accountClaim($tester['personId']);
        $client = FitMotApiClient::createForCreds(new CredentialsProvider($tester['username'], $tester['password']));

        try {
            $this->result = $client->put($urlBuilder, $this->data);
        } catch (ApiErrorException $e) {
            $this->result['errors'] = $e->getErrorsArray();
        }
    }

    public function reset()
    {
        $this->data = [];
        $this->result = null;
        $this->accountClaimRequired = false;
    }

    public function result()
    {
        if (isset($this->result['errors'])) {
            if ($this->result['errors'][0]['message'] === 'Forbidden') {
                return 'Forbidden';
            }

            return "errors";
        }

        return "Updated account";
    }

    public function errorMessage()
    {
        $errors = [];
        if (isset($this->result['errors'])) {
            $errors = array_map(
                function ($error) {
                    return $error['message'];
                },
                $this->result['errors']
            );
        }

        return implode(", ", $errors);
    }

    private function createFixtures()
    {
        $testSupportHelper = new TestSupportHelper();
        $this->testSupportHelper = $testSupportHelper;
        $schmMgr = $testSupportHelper->createSchemeManager();

        $authorisedExaminer = $testSupportHelper->createAuthorisedExaminer(
            $testSupportHelper->createAreaOffice1User()['username'],
            'CA' . __CLASS__
        );

        $vehicleTestingStation = $testSupportHelper->createVehicleTestingStation(
            $testSupportHelper->createAreaOffice1User()['username'],
            $authorisedExaminer['id'],
            'CA' . __CLASS__
        );

        $this->vtsId = $vehicleTestingStation['id'];
        $this->schmMgrUsername = $schmMgr['username'];

        $this->retrieveSecurityQuestions();
    }

    private function retrieveSecurityQuestions()
    {
        $tester = $this->createTester("SQProvider", true);
        $urlBuilder = (new UrlBuilder())->securityQuestion();
        $client = FitMotApiClient::createForCreds(new CredentialsProvider($tester['username'], $tester['password']));
        $securityQuestions = $client->get($urlBuilder);

        $this->securityQuestionsOne = array_filter(
            $securityQuestions,
            function ($question) {
                return $question['group'] === 1;
            }
        );

        $this->securityQuestionsTwo = array_filter(
            $securityQuestions,
            function ($question) {
                return $question['group'] === 2;
            }
        );
    }

    private function createTester($name, $accountClaimRequired = false)
    {
        if (isset($this->createdUsers[$name])) {
            return $this->createdUsers[$name];
        }

        $tester = $this->testSupportHelper->createTester(
            $this->schmMgrUsername,
            [$this->vtsId],
            null,
            $accountClaimRequired
        );

        $this->createdUsers[$name] = $tester;

        return $tester;
    }
}
