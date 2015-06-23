<?php
namespace DvsaMotTest\NewVehicle\Form;

use Zend\Form\Form;

class CreateVehicleStepThreeForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('VehicleStepThree');

        $this
            ->add(
                [
                    'name' => 'oneTimePassword',
                    'attributes' => [
                        'type' => 'password',
                        'id' => 'oneTimePassword',
                        'class' => 'form-control'
                    ]
                ]
            );
    }
}
