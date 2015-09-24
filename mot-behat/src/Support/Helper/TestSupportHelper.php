<?php

namespace Dvsa\Mot\Behat\Support\Helper;

use TestSupport\Controller\VM10619RoleManagementUpgradeController;
use TestSupport\Service\CronUserService;
use TestSupport\Service\CSCOService;
use TestSupport\Service\CSMService;
use TestSupport\Service\DVLAManagerService;
use TestSupport\Service\AreaOffice1Service;
use TestSupport\Service\AreaOffice2Service;
use TestSupport\Service\DVLAOperativeService;
use TestSupport\Service\FinanceUserService;
use TestSupport\Service\VM10619RoleManagementUpgradeService;
use TestSupport\Service\VtsService;
use TestSupport\Service\AEService;
use TestSupport\Service\TesterService;
use TestSupport\Service\PasswordResetService;
use TestSupport\Service\SchemeManagerService;
use TestSupport\Service\SchemeUserService;
use TestSupport\Service\VehicleService;
use TestSupport\Service\VehicleExaminerService;
use TestSupport\Service\UserService;
use TestSupport\Service\VM10519UserService;
use TestSupport\Service\AedmService;
use TestSupport\Service\AccountDataService;
use TestSupport\Service\SiteUserDataService;
use TestSupport\Helper\DataGeneratorHelper;
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
     * Factory to build user services depending on user role passed in.
     *
     * @param $userRole
     * @return \TestSupport\Service\DVLAManagerService|\TestSupport\Service\UserService
     * @throws \Exception
     */
    public function userRoleServiceFactory($userRole)
    {
        switch ($userRole) {
            case 'Scheme Manager':
                return $this->getSchemeManagerService();
            case 'Scheme User':
                return $this->getSchemeUserService();
            case 'DVLA Manager':
                return $this->getDVLAManagerService();
            case 'DVLA Operative':
                return $this->getDVLAOperativeService();
            case 'Vehicle Examiner':
                return $this->getVehicleExaminerService();
            case 'Area Office User':
                return $this->getAreaOffice1Service();
            case 'Area Office User 2':
                return $this->getAreaOffice2Service();
            case 'Customer Service Operative':
                return $this->getCscoService();
            case 'Customer Service Manager':
                return $this->getCSMService();
            case 'Finance User':
                return $this->getFinanceUserService();
            case 'User':
                return $this->getUserService();
        }
        throw new \Exception("Unknown service for role '{$userRole}'");
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

    public function getCronUserService()
    {
        return $this->getServiceManager()->get(CronUserService::class);
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
     * @return \TestSupport\Service\SchemeManagerService
     */
    public function getSchemeManagerService()
    {
        return $this->getServiceManager()->get(SchemeManagerService::class);
    }

    /**
     * @return \TestSupport\Service\SchemeUserService
     */
    public function getSchemeUserService()
    {
        return $this->getServiceManager()->get(SchemeUserService::class);
    }

    /**
     * @return \TestSupport\Service\UserService
     */
    public function getUserService()
    {
        return $this->getServiceManager()->get(UserService::class);
    }

    /**
     * @return \TestSupport\Service\CSMService
     */
    public function getCSMService()
    {
        return $this->getServiceManager()->get(CSMService::class);
    }

    /**
     * @return \TestSupport\Service\DVLAManagerService
     */
    public function getDVLAManagerService()
    {
        return $this->getServiceManager()->get(DVLAManagerService::class);
    }

    /**
     * @return \TestSupport\Service\VM10519UserService
     */
    public function getVM10519UserService()
    {
        return $this->getServiceManager()->get(VM10519UserService::class);

    }

    /**
     * @return \TestSupport\Service\VM10619RoleManagementUpgradeService
     */
    public function getVM10619RoleMananagementUpgradeService()
    {
        return $this->getServiceManager()->get(VM10619RoleManagementUpgradeService::class);
    }

    /**
     * @return \TestSupport\Service\DVLAOperativeService
     */
    public function getDVLAOperativeService()
    {
        return $this->getServiceManager()->get(DVLAOperativeService::class);
    }

    /**
     * @return AedmService
     */
    public function getAedmService()
    {
        return $this->getServiceManager()->get(AedmService::class);
    }

    /**
     * @return AccountDataService:
     */
    public function getAccountDataService()
    {
        return $this->getServiceManager()->get(AccountDataService::class);
    }

    /**
     * @return SiteUserDataService
     */
    public function getSiteUserDataService()
    {
        return $this->getServiceManager()->get(SiteUserDataService::class);
    }

    /**
     * @return \TestSupport\Service\SitePositionService
     */
    public function getSitePositionService()
    {
        return $this->getServiceManager()->get(SitePositionController::class);
    }

    /**
     * @param array $data
     * @return DataGeneratorHelper
     */
    public function getDataGeneratorHelper(array $data = [])
    {
        return DataGeneratorHelper::buildForDifferentiator($data);
    }

}
