<?php

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\Service;

use Dvsa\Mot\Frontend\AuthenticationModule\Controller\SecurityController;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\AuthenticationFailureViewModelBuilder;
use Dvsa\OpenAM\OpenAMAuthProperties;
use DvsaCommon\Authn\AuthenticationResultCode;
use DvsaCommon\Dto\Authn\AuthenticationResponseDto;
use DvsaCommon\Dto\Common\KeyValue;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\NonPersistent;

class AuthenticationFailureViewModelBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateFromAuthenticationResponse_givenAccountLocked_shouldReturnAppropriateModel()
    {
        $dto = (new AuthenticationResponseDto)->setAuthnCode(AuthenticationResultCode::ACCOUNT_LOCKED);
        $vm = $this->create()->createFromAuthenticationResponse($dto);
        $vars = $vm->getVariables();

        $this->assertEquals([
            'helpdesk' => ['helpdeskConfig'],
            'pageSubTitle' => SecurityController::PAGE_TITLE,
            'pageTitle' => 'Your account has been locked',
        ], $vars);

    }

    public function testCreateFromAuthenticationResponse_givenLockoutWarningWithMoreThan1AttemptLeft_shouldReturnAppropriateModel()
    {
        $dto = (new AuthenticationResponseDto)->setAuthnCode(AuthenticationResultCode::LOCKOUT_WARNING);
        $dto->setExtra(KeyValue::fromMap(['attemptsLeft' => 2]));
        $vm = $this->create()->createFromAuthenticationResponse($dto);
        $vars = $vm->getVariables();

        $this->assertEquals([
            'helpdesk'                       => $this->getHelpdeskConfig(),
            'pageSubTitle'                   => SecurityController::PAGE_TITLE,
            'pageTitle'                      => 'Authentication failed',
            'yourAccountWillBeLockedMessage' => 'Your account will be locked for 30 minutes if you enter an incorrect password 2 more times.',

        ], $vars);
    }

    public function dataProvider_attemptsLeftAndLockMessage() {
        return [
            [1, 'Your account will be locked for 30 minutes if you enter an incorrect password 1 more time.'],
            [2, 'Your account will be locked for 30 minutes if you enter an incorrect password 2 more times.']
        ];
    }

    /**
     * @dataProvider dataProvider_attemptsLeftAndLockMessage
     */
    public function testCreateFromAuthenticationResponse_givenLockoutWarningWithNAttempts_shouldReturnAppropriateModel(
        $attemptsLeft, $yourAccountWillBeLockedMessage
    )
    {
        $dto = (new AuthenticationResponseDto)->setAuthnCode(AuthenticationResultCode::LOCKOUT_WARNING);
        $dto->setExtra(KeyValue::fromMap(['attemptsLeft' => $attemptsLeft]));
        $vm = $this->create()->createFromAuthenticationResponse($dto);
        $vars = $vm->getVariables();

        $this->assertEquals($yourAccountWillBeLockedMessage,$vars['yourAccountWillBeLockedMessage']);
    }

    public function dataProvider_authnCodes() {
        return [
            [AuthenticationResultCode::INVALID_CREDENTIALS, 'authentication/failed/default'],
            [AuthenticationResultCode::ERROR, 'authentication/failed/default'],
            [AuthenticationResultCode::UNRESOLVABLE_IDENTITY, 'authentication/failed/default'],
            [AuthenticationResultCode::LOCKOUT_WARNING, 'authentication/failed/' . OpenAMAuthProperties::TEMPLATE_LOCKOUT_WARNING],
            [AuthenticationResultCode::ACCOUNT_LOCKED, 'authentication/failed/locked']
        ];
    }

    /** @dataProvider dataProvider_authnCodes */
    public function testCreateFromAuthenticationResponse_givenAuthnCode_shouldReturnAppropriateModel($authnCode, $template)
    {
        $dto = (new AuthenticationResponseDto)->setAuthnCode($authnCode);
        $dto->setExtra(KeyValue::fromMap(['attemptsLeft' => 1]));
        $vm = $this->create()->createFromAuthenticationResponse($dto);

        $this->assertEquals($template, $vm->getTemplate());
    }


    private function create()
    {
        return new AuthenticationFailureViewModelBuilder(['helpdeskConfig']);
    }

    private function getHelpdeskConfig() {
        return ['helpdeskConfig'];
    }

}