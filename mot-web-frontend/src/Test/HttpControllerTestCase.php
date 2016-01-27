<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\Test;

use Application\Service\LoggedInUserManager;
use Core\Service\LazyMotFrontendAuthorisationService;
use Core\Service\MotFrontendIdentityProvider;
use CoreTest\Service\StubCatalogService;
use Dvsa\Mot\Frontend\AuthenticationModule\Model\Identity;
use DvsaCommon\Auth\MotIdentityProvider;
use DvsaCommon\HttpRestJson\Client as HttpRestJsonClient;
use DvsaCommon\Model\ListOfRolesAndPermissions;
use DvsaCommon\Model\PersonAuthorization;
use DvsaFeature\FeatureToggles;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\NonPersistent;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Base class for testing HTTP Controllers.
 */
abstract class HttpControllerTestCase extends AbstractHttpControllerTestCase
{
    /**
     * @var string
     */
    private $rootDir;

    public function setUp()
    {
        $config = $this->prepareTestConfig(require $this->getRootDir() . '/test/test.config.php');
        $this->setApplicationConfig($config);

        parent::setUp();

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);

        /** @var HttpRestJsonClient $restClient */
        $restClient = $this
            ->getMockBuilder(HttpRestJsonClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $serviceManager->setService('CatalogService', new StubCatalogService());
        $serviceManager->setService(HttpRestJsonClient::class, $restClient);
        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asAnonymous());
    }

    /**
     * @param array $featureToggles
     *
     * @return $this
     */
    protected function withFeatureToggles(array $featureToggles = [])
    {
        $map = [];
        foreach ($featureToggles as $name => $value) {
            $map += [(string) $name, (bool) $value];
        }

        $featureToggles = $this
            ->getMockBuilder(FeatureToggles::class)
            ->disableOriginalConstructor()
            ->getMock();
        $featureToggles
            ->method('isEnabled')
            ->will($this->returnValueMap($map));

        $this->getApplicationServiceLocator()->setService('Feature\FeatureToggles', $featureToggles);

        return $this;
    }

    /**
     * @param StubIdentityAdapter $identityAdapter
     */
    protected function setupAuthenticationServiceForIdentity(StubIdentityAdapter $identityAdapter)
    {
        $authenticationService = new AuthenticationService(new NonPersistent(), $identityAdapter);
        $authenticationService->clearIdentity();

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setService('ZendAuthenticationService', $authenticationService);

        /** @var Result $result */
        $result = $authenticationService->authenticate();

        // Need to replace all references to the old authentication service and identity
        /** @var MotFrontendIdentityProvider $motIdentityProvider */
        $motIdentityProvider = $serviceManager->get('MotIdentityProvider');
        $motIdentityProvider->setZendAuthenticationService($authenticationService);

        /** @var LoggedInUserManager $loggedInUserManager */
        $loggedInUserManager = $serviceManager->get('LoggedInUserManager');
        $loggedInUserManager->setIdentityProvider($motIdentityProvider);

        $this->createNewAuthorizationService($result->isValid() ? $result->getIdentity() : null);
    }

    /**
     * @param array $grantedPermissions
     * @param array $grantedRoles
     */
    protected function setupAuthorizationService($grantedPermissions = [], $grantedRoles = [])
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);

        $motIdentity = new Identity();
        $motIdentity->setPersonAuthorization(
            new PersonAuthorization(
                new ListOfRolesAndPermissions($grantedRoles, $grantedPermissions),
                [], [], []
            )
        );
        $this->createNewAuthorizationService($motIdentity);
    }

    /**
     * @var Identity
     */
    protected function createNewAuthorizationService($motIdentity)
    {
        $serviceManager = $this->getApplicationServiceLocator();

        /** @var HttpRestJsonClient $restClient */
        $restClient = $serviceManager->get(HttpRestJsonClient::class);
        $authorisationService = new LazyMotFrontendAuthorisationService(new MotIdentityProvider($motIdentity), $restClient);

        $serviceManager->setService('AuthorisationService', $authorisationService);
    }

    /** @return Identity */
    protected function getCurrentIdentity()
    {
        return $this->getApplicationServiceLocator()->get('MotIdentityProvider')->getIdentity();
    }

    /**
     * @return string
     */
    private function getRootDir()
    {
        if (null === $this->rootDir) {
            $this->rootDir = realpath(__DIR__ . '/../../');
        }

        return $this->rootDir;
    }

    /**
     * @param array $testConfig
     *
     * @return array
     */
    private function prepareTestConfig($testConfig)
    {
        unset($testConfig['test_namespaces']);

        $testConfig = array_merge_recursive([
            'module_listener_options' => [
                'module_paths'      => [
                    $this->getRootDir() . '/module',
                    $this->getRootDir() . '/vendor',
                ],
                'config_glob_paths' => [],
                'config_cache_enabled'     => false,
                'module_map_cache_enabled' => false,
                'check_dependencies'       => true,
            ],
        ], $testConfig);

        return $testConfig;
    }
}
