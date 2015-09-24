<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModule\Service;

use AccountApi\Crypt\SecurityAnswerHashFunction;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\PersonSecurityAnswer;
use DvsaEntities\Entity\SecurityQuestion;
use DvsaEntities\Repository\SecurityQuestionRepository;

/**
 * Class PersonSecurityAnswerServiceTest.
 */
class PersonSecurityAnswerRecorderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PersonSecurityAnswerRecorder
     */
    private $subject;

    public function setUp()
    {
        /** @var SecurityQuestionRepository $mockSecurityQuestionRepository */
        $mockSecurityQuestionRepository = XMock::of(SecurityQuestionRepository::class);
        $mockSecurityQuestionRepository->expects($this->any())
            ->method('find')
            ->willReturn(new SecurityQuestion());

        $securityAnswerHashFunction = new SecurityAnswerHashFunction();

        $this->subject = new PersonSecurityAnswerRecorder(
            $mockSecurityQuestionRepository,
            $securityAnswerHashFunction
        );
    }

    /**
     * @dataProvider dpStepDetails
     *
     * @param int    $questionId
     * @param string $answer
     */
    public function testCreate($questionId, $answer)
    {
        $this->assertInstanceOf(
            PersonSecurityAnswer::class,
            $this->subject->create(new Person(), $questionId, $answer)
        );
    }

    public function dpStepDetails()
    {
        return [
            [

                    1,
                    'Something',

                    2,
                    'Something else',

            ],
        ];
    }
}
