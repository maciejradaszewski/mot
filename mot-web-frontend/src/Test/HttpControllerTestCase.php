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
use Dvsa\OpenAM\OpenAMClient;
use Dvsa\OpenAM\OpenAMClientInterface;
use DvsaCommon\Auth\MotIdentityProvider;
use DvsaCommon\HttpRestJson\Client as HttpRestJsonClient;
use DvsaCommon\Model\ListOfRolesAndPermissions;
use DvsaCommon\Model\PersonAuthorization;
use DvsaFeature\FeatureToggles;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\NonPersistent;
use Zend\Stdlib\ArrayUtils;
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
        putenv('APPLICATION_ENV=development');
        $config = require_once $this->getRootDir() . '/config/application.config.php';
        $this->setApplicationConfig($this->processApplicationConfig($config));

        parent::setUp();

        $this->init();
    }

    protected function init()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);

        $serviceManager->setService(OpenAMClientInterface::class, $this->createOpenAMClient());
        $serviceManager->setService('CatalogService', new StubCatalogService());
        $serviceManager->setService(HttpRestJsonClient::class, $this->createRestClient());
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
     * Generates a URL based on a route.
     *
     * @param string     $route   RouteInterface name
     * @param array      $params  Parameters to use in url generation, if any
     * @param array|bool $options RouteInterface-specific options to use in url generation, if any.
     *
     * @return string
     */
    protected function generateUrlFromRoute($route, $params = [], $options = [])
    {
        $options['name'] = $route;

        return $this->getApplicationServiceLocator()->get('Router')->assemble($params, $options);
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
    protected function setupAuthorisationService($grantedPermissions = [], $grantedRoles = [])
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
     * Mocks the OpenAMClient that is now used from the web frontend.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createOpenAMClient()
    {
        $mockOpenAMClient = $this->getMockBuilder(OpenAMClient::class)
            ->disableOriginalConstructor()
            ->setMethods(['validateCredentials'])
            ->getMock();
        $mockOpenAMClient->expects($this->any())
            ->method('validateCredentials')
            ->will($this->returnValue(true));

        return $mockOpenAMClient;
    }

    /**
     * @return HttpRestJsonClient
     */
    private function createRestClient()
    {
        return $this
            ->getMockBuilder(HttpRestJsonClient::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param array $appConfig
     *
     * @return array
     */
    private function processApplicationConfig($appConfig)
    {
        unset($appConfig['module_listener_options']['config_glob_paths']);

        return  ArrayUtils::merge($appConfig, [
            'module_listener_options' => [
                'config_glob_paths' => [
                    'config/testing/global.php',
                    'local-test.php',
                ],
                'config_cache_enabled'     => false,
                'module_map_cache_enabled' => false,
                'check_dependencies'       => true,
            ],
        ]);
    }
}
