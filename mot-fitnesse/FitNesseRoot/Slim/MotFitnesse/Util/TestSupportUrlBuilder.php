<?php

namespace MotFitnesse\Util;

/**
 * Class TestSupportUrlBuilder
 *
 * @package MotFitnesse\Util
 */
class TestSupportUrlBuilder extends AbstractUrlBuilder
{
    const TEST_SUPPORT                                  = '/testsupport';
    const CREATE_USER_SCHEME_MANAGER                    = '/schm';
    const CREATE_USER_SCHEME_USER                       = '/schemeuser';
    const CREATE_USER_AE                                = '/ae';
    const CREATE_USER_AEDM                              = '/aedm';
    const CREATE_USER_AED                               = '/aed';
    const CREATE_USER_VTS                               = '/vts';
    const CREATE_USER_TESTER                            = '/tester';
    const CREATE_INACTIVE_TESTER                        = '/inactivetester';
    const CREATE_USER_USER                              = '/user';
    const CREATE_USER_SITE_MANAGER                      = '/sm';
    const CREATE_USER_SITE_ADMIN                        = '/sa';
    const CREATE_USER_AREA_OFFICE1                      = '/areaoffice1';
    const CREATE_USER_AREA_OFFICE2                      = '/areaoffice2';
    const CREATE_USER_ASSESSOR                          = '/assessor';
    const CREATE_USER_VEHICLE_EXAMINER                  = '/vehicleexaminer';
    const CREATE_USER_CUSTOMER_SERVICE_CENTRE_OPERATIVE = '/csco';
    const CREATE_USER_DVLA_OPERATIVE                    = '/dvlaoperative';
    const CREATE_SLOT_TRANSACTION                       = '/slot-transaction';
    const CREATE_FINANCE_USER                           = '/financeuser';

    const CREATE_TEST_MOT_TEST = '/mottest';

    const TEST_SUPPORT_VEHICLE         = '/vehicle';
    const TEST_SUPPORT_VEHICLE_ADD_V5C = '/v5c-add';

    const TEST_SUPPORT_DVLA_VEHICLE = '/dvla-vehicle/create';

    const TEST_SUPPORT_SPECIAL_NOTICE           = '/special-notice';
    const TEST_SUPPORT_SPECIAL_NOTICE_BROADCAST = '/broadcast';
    const TEST_SUPPORT_SPECIAL_NOTICE_CREATE    = '/create';

    const DB_RESET = '/reset';

    const CREATE_EVENT      = '/event/create';
    const SECURITY_QUESTION = '/security-question/create';
    const RESET_PASSWORD = '/reset-password';

    protected $routesStructure
        = [
            self::TEST_SUPPORT => [
                self::CREATE_SLOT_TRANSACTION                       => '',
                self::CREATE_USER_SCHEME_MANAGER                    => '',
                self::CREATE_USER_SCHEME_USER                       => '',
                self::CREATE_USER_AE                                => '',
                self::CREATE_USER_AEDM                              => '',
                self::CREATE_USER_AED                               => '',
                self::CREATE_USER_VTS                               => [
                    self::CREATE_USER_SITE_MANAGER => '',
                    self::CREATE_USER_SITE_ADMIN   => '',
                ],
                self::CREATE_USER_TESTER                            => '',
                self::CREATE_INACTIVE_TESTER                        => '',
                self::CREATE_USER_USER                              => '',
                self::CREATE_USER_AREA_OFFICE1                      => '',
                self::CREATE_USER_AREA_OFFICE2                      => '',
                self::CREATE_FINANCE_USER                           => '',
                self::CREATE_USER_ASSESSOR                          => '',
                self::CREATE_USER_VEHICLE_EXAMINER                  => '',
                self::CREATE_USER_CUSTOMER_SERVICE_CENTRE_OPERATIVE => '',
                self::CREATE_TEST_MOT_TEST                          => '',
                self::TEST_SUPPORT_VEHICLE                          => [
                    self::TEST_SUPPORT_VEHICLE_ADD_V5C => ''
                ],
                self::TEST_SUPPORT_DVLA_VEHICLE => '',
                self::CREATE_USER_DVLA_OPERATIVE                    => '',
                self::TEST_SUPPORT_SPECIAL_NOTICE                   => [
                    self::TEST_SUPPORT_SPECIAL_NOTICE_BROADCAST => '',
                    self::TEST_SUPPORT_SPECIAL_NOTICE_CREATE    => '',
                ],
                self::CREATE_EVENT                                  => '',
                self::SECURITY_QUESTION                             => '',
                self::RESET_PASSWORD                                => '',
            ],
            self::DB_RESET     => '',
        ];

    public function __construct()
    {
        $this->forBaseUrl(TestBase::TESTSUPPORT_URL);
    }

    public function testSupport()
    {
        $this->routesAndParameters[]['route'] = self::TEST_SUPPORT;

        return $this;
    }

    public function createSchemeManager()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_SCHEME_MANAGER;

        return $this;
    }

    public function createSchemeUser()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_SCHEME_USER;

        return $this;
    }

    public function slotTxn()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_SLOT_TRANSACTION;

        return $this;
    }

    public function createAuthorisedExaminer()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_AE;

        return $this;
    }

    public function createAuthorisedExaminerDelegateManager()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_AEDM;

        return $this;
    }

    public function createAuthorisedExaminerDesignated()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_AED;

        return $this;
    }

    /**
     * @return TestSupportUrlBuilder
     */
    public function createVehicleTestingStation()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_VTS;

        return $this;
    }

    public function createTester()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_TESTER;

        return $this;
    }

    public function createInactiveTester()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_INACTIVE_TESTER;

        return $this;
    }

    public function createUser()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_USER;

        return $this;
    }

    public function createSiteManager()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_SITE_MANAGER;

        return $this;
    }

    public function createSiteAdmin()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_SITE_ADMIN;

        return $this;
    }

    public function createAreaOffice1()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_AREA_OFFICE1;

        return $this;
    }

    public function createAreaOffice2()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_AREA_OFFICE2;

        return $this;
    }

    public function createFinanceUser()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_FINANCE_USER;

        return $this;
    }

    public function createAssessor()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_ASSESSOR;

        return $this;
    }

    public function createVehicleExaminer()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_USER_VEHICLE_EXAMINER;

        return $this;
    }

    public function createMotTest()
    {
        $this->routesAndParameters[]['route'] = self::CREATE_TEST_MOT_TEST;

        return $this;
    }

    public function createDvlaVehicle()
    {
        $this->appendRoutesAndParams(self::TEST_SUPPORT_DVLA_VEHICLE);

        return $this;
    }

    public function createCustomerServiceCentreOperative()
    {
        $this->appendRoutesAndParams(self::CREATE_USER_CUSTOMER_SERVICE_CENTRE_OPERATIVE);

        return $this;
    }

    public function dbReset()
    {
        return $this->appendRoutesAndParams(self::DB_RESET);
    }

    public function vehicleAddV5c()
    {
        return $this
            ->appendRoutesAndParams(self::TEST_SUPPORT)
            ->appendRoutesAndParams(self::TEST_SUPPORT_VEHICLE)
            ->appendRoutesAndParams(self::TEST_SUPPORT_VEHICLE_ADD_V5C);
    }

    public function createDvlaOperative()
    {
        $this->appendRoutesAndParams(self::CREATE_USER_DVLA_OPERATIVE);

        return $this;
    }

    public function specialNotice()
    {
        $this->appendRoutesAndParams(self::TEST_SUPPORT_SPECIAL_NOTICE);

        return $this;
    }

    public function broadcastSpecialNotice()
    {
        $this->appendRoutesAndParams(self::TEST_SUPPORT_SPECIAL_NOTICE_BROADCAST);

        return $this;
    }

    public function createSpecialNotice()
    {
        $this->appendRoutesAndParams(self::TEST_SUPPORT_SPECIAL_NOTICE_CREATE);

        return $this;
    }

    /**
     * Followings are to be used as shortcut method mimicking last segment of existing end-points at mot-testsupport
     **/

    public function schm()
    {
        return $this->testSupport()->createSchemeManager();
    }

    public function schemeuser()
    {
        return $this->testSupport()->createSchemeUser();
    }

    public function ae()
    {
        return $this->testSupport()->createAuthorisedExaminer();
    }

    public function aed()
    {
        return $this->testSupport()->createAuthorisedExaminerDesignated();
    }

    public function aedm()
    {
        return $this->testSupport()->createAuthorisedExaminerDelegateManager();
    }

    public function vts()
    {
        return $this->testSupport()->createVehicleTestingStation();
    }

    public function tester()
    {
        return $this->testSupport()->createTester();
    }

    public function inactivetester() {
        return $this->testSupport()->createInactiveTester();
    }

    public function user()
    {
        return $this->testSupport()->createUser();
    }

    public function sm()
    {
        return $this->testSupport()->createVehicleTestingStation()->createSiteManager();
    }

    public function sa()
    {
        return $this->testSupport()->createVehicleTestingStation()->createSiteAdmin();
    }

    public function ao1()
    {
        return $this->testSupport()->createAreaOffice1();
    }

    public function ao2()
    {
        return $this->testSupport()->createAreaOffice2();
    }

    public function financeuser()
    {
        return $this->testSupport()->createFinanceUser();
    }

    public function assessor()
    {
        return $this->testSupport()->createAssessor();
    }

    public function ve()
    {
        return $this->testSupport()->createVehicleExaminer();
    }

    public function mottest()
    {
        return $this->testSupport()->createMotTest();
    }

    public function csco()
    {
        return $this->testSupport()->createCustomerServiceCentreOperative();
    }

    public function dvlaop()
    {
        return $this->testSupport()->createDvlaOperative();
    }

    public function vehicleexaminer()
    {
        return $this->testSupport()->createVehicleExaminer();
    }

    public function createEvent()
    {
        return $this->appendRoutesAndParams(self::CREATE_EVENT);
    }

    public function generateSecurityQuestion()
    {
        return $this->appendRoutesAndParams(self::SECURITY_QUESTION);
    }

    public function resetPassword()
    {
        return $this->appendRoutesAndParams(self::RESET_PASSWORD);
    }
}
