<?php

namespace Dashboard\Form;

use Zend\Form\Form;
use Zend\Validator\NotEmpty;
use Core\Service\MotFrontendIdentityProviderInterface;
use DvsaCommon\InputFilter\Account\ChangePasswordInputFilter;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Model\OpenAMLoginDetails;
use Dvsa\OpenAM\Exception\OpenAMClientException;

class ChangePasswordForm extends PasswordForm
{
    const FIELD_OLD_PASSWORD = ChangePasswordInputFilter::FIELD_OLD_PASSWORD;
    const LABEL_OLD_PASSWORD = "Current password";

    private $identityProvider;
    private $client;
    private $realm;

    public function __construct(
        MotFrontendIdentityProviderInterface $identityProvider,
        OpenAMClientInterface $client,
        $realm,
        $options = [])
    {
        parent::__construct($identityProvider, $options);

        $this->identityProvider = $identityProvider;
        $this->client = $client;
        $this->realm = $realm;

        $this->add(
            [
                'name' => self::FIELD_OLD_PASSWORD,
                'type' => 'password',
                'options' => [
                    'label' => self::LABEL_OLD_PASSWORD
                ],
                'attributes' => [
                    'id' => self::FIELD_OLD_PASSWORD,
                    'maxlength' => self::PASSWORD_MAX_LENGTH,
                    'group' => true
                ],
            ]
        );

        $this->setInputFilter($this->createInputFilter());
    }

    public function isValid()
    {
        $isValid = parent::isValid();

        $username = $this->identityProvider->getIdentity()->getUsername();
        $password = $this->get(ChangePasswordInputFilter::FIELD_OLD_PASSWORD)->getValue();
        $loginDetails = new OpenAMLoginDetails($username, $password, $this->realm);

        try {
            $this->client->validateCredentials($loginDetails);
        } catch (OpenAMClientException $e) {
            $isValid = false;
            $this->get(ChangePasswordInputFilter::FIELD_OLD_PASSWORD)->setMessages([ChangePasswordInputFilter::MSG_PASSWORD_INVALID]);
        }

        return $isValid;
    }

    private function createInputFilter()
    {
        $inputFilter = new ChangePasswordInputFilter($this->identityProvider);
        $inputFilter->init();

        return $inputFilter;
    }
}
