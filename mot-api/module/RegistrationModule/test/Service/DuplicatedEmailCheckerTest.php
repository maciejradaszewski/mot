<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModuleTest\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Dvsa\Mot\Api\RegistrationModule\Controller\RegistrationController;
use Dvsa\Mot\Api\RegistrationModule\Service\DuplicatedEmailChecker;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Email;

/**
 * Class DuplicatedEmailCheckerTest
 */
class DuplicatedEmailCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DuplicatedEmailChecker
     */
    private $subject;

    const EXISTING_EMAIL = 'existingduplicatedemailcheckertestr@dvsa.test';
    const NON_EXISTING_EMAIL = 'non-existingduplicatedemailcheckertest@dvsa.test';

    public function setUp()
    {
        $this->subject = new DuplicatedEmailChecker(
            $this->getMockEmailRepository()
        );
    }

    public function testIsEmailDuplicated()
    {
        $this->assertTrue($this->subject->isEmailDuplicated(self::EXISTING_EMAIL));
        $this->assertFalse($this->subject->isEmailDuplicated(self::NON_EXISTING_EMAIL));
    }

    private function getMockEmailRepository()
    {
        $mockEmailRepository = XMock::of(EntityRepository::class);

        $mockEmailRepository->expects($this->any())
            ->method('findBy')
            ->willReturnCallback(
                function ($args) {
                    switch ($args) {
                        case [RegistrationController::KEY_EMAIL => self::EXISTING_EMAIL, 'isPrimary' => 1] :
                            return [new Email()];
                            break;
                        case [RegistrationController::KEY_EMAIL => self::EXISTING_EMAIL, 'isPrimary' => 1] :
                            return [];
                            break;
                    }
                }
            );

        return $mockEmailRepository;
    }
}
