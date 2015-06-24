<?php

namespace AccountTest\ViewModel;

use Account\ViewModel\ReviewViewModel;

class ReviewViewModelTest extends \PHPUnit_Framework_TestCase
{

    const NO_EMAIL_PROVIDED_MESSAGE = 'Not provided';

    /** @var  ReviewViewModel */
    private $model;

    public function setUp()
    {
        $this->model = new ReviewViewModel();
    }

    public function testSetDataWithNoEmailWillReturnNotProvided()
    {
        $testData = $this->getTestData();
        $testData['confirmEmailAndPassword'] = '';

        $this->model->setData($testData);

        $this->assertEquals(self::NO_EMAIL_PROVIDED_MESSAGE, $this->model->getEmail());
    }

    public function testSetDataWillReturnDtoWithGettersMatchingArray()
    {
        $testData = $this->getTestData();

        $this->model->setData($testData);

        $this->assertEquals($this->model->getEmail(), $testData['confirmEmailAndPassword']['email']);
        $this->assertEquals($this->model->getPassword(), $testData['confirmEmailAndPassword']['password']);
        $this->assertEquals($this->model->getSecurityQuestions(), $testData['securityQuestions']);
        $this->assertEquals($this->model->getAnswerA(), $testData['setSecurityQuestion']['answer_a']);
        $this->assertEquals($this->model->getAnswerB(), $testData['setSecurityQuestion']['answer_b']);
        $this->assertEquals($this->model->getSecurityQuestionA(), $testData['setSecurityQuestion']['question_a']);
        $this->assertEquals($this->model->getSecurityQuestionB(), $testData['setSecurityQuestion']['question_b']);
    }

    public function testSetSecurityQuestionDataWillReturnTheQuestionRelevantToTheUsersSelection()
    {
        $testData = $this->getTestData();

        $this->model->setData($testData);

        $securityQuestions = $testData['securityQuestions'];
        $groupAQuestions = $securityQuestions['groupA'];
        $groupBQuestions = $securityQuestions['groupB'];

        $this->assertEquals($this->model->getSecurityQuestionGroupAText(), $groupAQuestions[$this->model->getSecurityQuestionA()]);
        $this->assertEquals($this->model->getSecurityQuestionGroupBText(), $groupBQuestions[$this->model->getSecurityQuestionB()]);
    }

    public function testSetDataWithPasswordOnReturnDisplayPasswordWillReturnHiddenCharacterString()
    {
        $testData = $this->getTestData();

        $this->model->setData($testData);

        $passwordChar = str_repeat('•', strlen($testData['confirmEmailAndPassword']['password']));
        $encryptedPasswordChar = $this->model->getHiddenPassword();

        $this->assertEquals($passwordChar, $encryptedPasswordChar);
    }

    public function testSetDataWithAnswersOnReturnDisplayAnswersWillReturnHiddenCharacterString()
    {
        $testData = $this->getTestData();

        $this->model->setData($testData);

        $answerA = str_repeat('•', strlen($testData['setSecurityQuestion']['answer_a']));
        $hiddenAnswerA = $this->model->getHiddenAnswerA();

        $this->assertEquals($answerA, $hiddenAnswerA);

        $answerB = str_repeat('•', strlen($testData['setSecurityQuestion']['answer_b']));
        $hiddenAnswerB = $this->model->getHiddenAnswerB();

        $this->assertEquals($answerB, $hiddenAnswerB);
    }

    private function getTestData()
    {
        return [
            'email' => 'test@test.com',
            'confirmEmailAndPassword' => [
                'password' => 'Password1',
                'email' => 'test2@test2.com'
            ],
            'setSecurityQuestion' => [
                'question_a' => '1',
                'question_b' => '2',
                'answer_a' => 'test',
                'answer_b' => 'test2',
            ],
            'securityQuestions' => [
                'groupA' =>
                    [
                        1 => 'What is your Fav Car'
                    ]
                ,
                'groupB' =>
                    [
                        2 => 'What is your Fav Colour'
                    ]
            ]
        ];
    }

}
