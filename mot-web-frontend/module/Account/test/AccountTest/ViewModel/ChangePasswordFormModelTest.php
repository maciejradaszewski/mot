<?php

namespace AccountTest\ViewModel;

use Account\ViewModel\ChangePasswordFormModel;

class ChangePasswordFormModelTest extends \PHPUnit_Framework_TestCase
{
    /** @var  ChangePasswordFormModel */
    private $model;

    public function setUp()
    {
        $this->model = new ChangePasswordFormModel();
    }

    /**
     * @dataProvider dataProviderTestIsValid
     */
    public function testIsValid($passw, $passConf, $username, $expect)
    {
        $this->model->setUsername($username);

        $this->model->populateFromPost(
            [
                ChangePasswordFormModel::FIELD_PASS => $passw,
                ChangePasswordFormModel::FIELD_PASS_CONFIRM => $passConf
            ]
        );

        $this->assertFalse($this->model->isValid());

        $this->assertEquals($expect['msg'], $this->model->getError($expect['field']));
    }

    public function dataProviderTestIsValid()
    {
        return [
            [
                'pass'     => '',
                'passConf' => '',
                'username' => 'tester1',
                'expect'   => [
                    'field' => ChangePasswordFormModel::FIELD_PASS,
                    'msg'   => ChangePasswordFormModel::ERR_REQUIRED,
                ],
            ],

            //  --  confirmation is not same    --
            [
                'pass'     => 'Aa345678',
                'passConf' => 'not same',
                'username' => 'tester1',
                'expect'   => [
                    'field' => ChangePasswordFormModel::FIELD_PASS_CONFIRM,
                    'msg'   => ChangePasswordFormModel::ERR_NOT_SAME,
                ],
            ],

            //  --  confirmation username is not password
            [
                'pass'     => 'tester1',
                'passConf' => '',
                'username' => 'tester1',
                'expect'   => [
                    'field' => ChangePasswordFormModel::FIELD_PASS,
                    'msg'   => ChangePasswordFormModel::ERR_NOT_USERNAME,
                ],
            ],
        ];
    }

    public function testSettersAndGetters()
    {
        $this->model->setTryAgainLink(true);
        $this->assertTrue($this->model->isTryAgainLink());
    }
}
