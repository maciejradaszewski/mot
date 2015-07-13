<?php

namespace Dvsa\Mot\Behat\Support\Helper;

use TestSupport\Service\CSCOService;
use TestSupport\Service\AreaOffice1Service;
use TestSupport\Service\AreaOffice2Service;
use TestSupport\Service\FinanceUserService;
use TestSupport\Service\VtsService;
use TestSupport\Service\AEService;
use TestSupport\Service\TesterService;
use TestSupport\Service\PasswordResetService;
use TestSupport\Service\VehicleService;
use TestSupport\Service\VehicleExaminerService;
use TestSupport\Service\VM10519UserService;
use DvsaCommon\Obfuscate\ParamObfuscator;
use Zend\ServiceManager\ServiceManager;

class TestSupportHelper
{
    /**
     * @var ServiceManager
     */
    private $testSupportServiceManager;

    /**
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->testSupportServiceManager = $serviceManager;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->testSupportServiceManager;
    }

    /**
     * Retrieve the CSCOService from the ServiceManager
     * @return \TestSupport\Service\CSCOService
     */
    public function getCscoService()
    {
        return $this->getServiceManager()->get(CSCOService::class);
    }

    /**
     * Retrieve the AreaOffice1Service from the ServiceManager
     * @return \TestSupport\Service\AreaOffice1Service
     */
    public function getAreaOffice1Service()
    {
        return $this->getServiceManager()->get(AreaOffice1Service::class);
    }
    /**
     * Retrieve the FinanceUserService from the ServiceManager
     * @return \TestSupport\Service\AreaOffice1Service
     */
    public function getFinanceUserService()
    {
        return $this->getServiceManager()->get(FinanceUserService::class);
    }

    /**
     * Retrieve the AreaOffice2Service from the ServiceManager
     * @return \TestSupport\Service\AreaOffice2Service
     */
    public function getAreaOffice2Service()
    {
        return $this->getServiceManager()->get(AreaOffice2Service::class);
    }

    /**
     * Retrieve the VtsService from the TestSupport ServiceManager
     * @return \TestSupport\Service\VtsService
     */
    public function getVtsService()
    {
        return $this->getServiceManager()->get(VtsService::class);
    }

    /**
     * @return \TestSupport\Service\AEService
     */
    public function getAeService()
    {
        return $this->getServiceManager()->get(AEService::class);
    }

    /**
     * @return \TestSupport\Service\TesterService
     */
    public function getTesterService()
    {
        return $this->getServiceManager()->get(TesterService::class);
    }

    /**
     * @return \TestSupport\Service\PasswordResetService
     */
    public function getPasswordResetService()
    {
        return $this->getServiceManager()->get(PasswordResetService::class);
    }

    /**
     * @return \TestSupport\Service\VehicleService
     */
    public function getVehicleService()
    {
        return $this->getServiceManager()->get(VehicleService::class);
    }

    /**
     * @return \DvsaCommon\Obfuscate\ParamObfuscator
     */
    public function getParamObfuscatorService()
    {
        return $this->getServiceManager()->get(ParamObfuscator::class);
    }

    /**
     * @return \TestSupport\Service\VehicleExaminerService
     */
    public function getVehicleExaminerService()
    {
        return $this->getServiceManager()->get(VehicleExaminerService::class);
    }

    /**
     * @return \TestSupport\Service\VM10519UserService
     */
    public function getSuperVehicleExaminerService()
    {
        return $this->getServiceManager()->get(VM10519UserService::class);
    }
}
