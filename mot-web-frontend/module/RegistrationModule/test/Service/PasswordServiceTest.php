<?php

namespace Dvsa\Mot\Frontend\RegistrationModuleTest\Service;

use Dvsa\Mot\Frontend\RegistrationModule\Service\PasswordService;
use DvsaCommon\Validator\PasswordValidator;
use DvsaCommonTest\TestUtils\XMock;

class PasswordServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $validator = XMock::of(PasswordValidator::class);

        $passwordService = new PasswordService($validator);
        $this->assertInstanceOf(PasswordService::class, $passwordService);
    }

    /**
     * @dataProvider dpValidatePassword
     */
    public function testPasswordValidation($password, $expected)
    {
        $validator = XMock::of(PasswordValidator::class);
        $validator->expects($this->once())
            ->method('isValid')
            ->with($password)
            ->willReturn($expected);

        $passwordService = new PasswordService($validator);
        $result = $passwordService->validatePassword($password);

        $this->assertSame($expected, $result, "Password does not meet requirements {$password}");
    }

    public function dpValidatePassword()
    {
        return [
            ['good', true],
            ['bad', false],
        ];
    }
}
