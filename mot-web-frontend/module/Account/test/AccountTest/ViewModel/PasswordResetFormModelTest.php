<?php

namespace AccountTest\ViewModel;

use Account\ViewModel\PasswordResetFormModel;

class PasswordResetFormModelTest extends \PHPUnit_Framework_TestCase
{
    const USERNAME = 'USERNAME';
    const EXPIRY_TIME = 900;

    public function testGetterSetter()
    {
        $model = new PasswordResetFormModel();

        $this->assertInstanceOf(PasswordResetFormModel::class, $model->setUsername(self::USERNAME));
        $this->assertInstanceOf(PasswordResetFormModel::class, $model->setCfgExpireTime(self::EXPIRY_TIME));
        $this->assertSame(self::USERNAME, $model->getUsername());
        $this->assertSame(self::EXPIRY_TIME, $model->getCfgExpireTime());

        $this->assertTrue($model->isValid());
        $model->populateFromPost([]);
        $this->assertFalse($model->isValid());

        $this->assertSame('/forgotten-password', $model->getCurrentPage()->toString());
    }
}
