<?php

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm4842CheckSecurityQuestions
{
    private $cscoName;
    private $caller;
    private $response;

    public function withCscoUser($name)
    {
        $this->cscoName = $name;
    }

    public function forCaller($caller)
    {
        $this->caller = $caller;
    }

    public function answerAgainstQuestion($correctAnswer, $questionId)
    {
        $url = (new UrlBuilder())->securityQuestionAnswer()
            ->routeParam("qid", $questionId)
            ->routeParam("uid", $this->caller)
            ->queryParam("answer", $correctAnswer);

        $this->response = TestShared::execCurlForJsonFromUrlBuilder(
            new \MotFitnesse\Util\CredentialsProvider(
                $this->cscoName,
                TestShared::PASSWORD
            ),
            $url
        );

        if (isset($this->response['data'])) {
            return $this->response['data'];
        }

        return false;
    }
}
