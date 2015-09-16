<?php

namespace Dashboard\Form;

use Zend\Form\Form;
use Zend\Validator\NotEmpty;
use Core\Service\MotFrontendIdentityProviderInterface;
use DvsaCommon\InputFilter\Account\ChangePasswordInputFilter;

class ChangePasswordForm extends PasswordForm
{
    const FIELD_OLD_PASSWORD = ChangePasswordInputFilter::FIELD_OLD_PASSWORD;
    const LABEL_OLD_PASSWORD = "Old password";

    private $identityProvider;

    public function __construct(MotFrontendIdentityProviderInterface $identityProvider, $options = [])
    {
        parent::__construct($identityProvider, $options);

        $this->identityProvider = $identityProvider;

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

    private function createInputFilter()
    {
        $inputFilter = new ChangePasswordInputFilter($this->identityProvider);
        $inputFilter->init();

        return $inputFilter;
    }
}
