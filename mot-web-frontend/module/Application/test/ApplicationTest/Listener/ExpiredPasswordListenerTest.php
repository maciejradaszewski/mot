<?php

namespace ApplicationTest\Listener;


use Account\Service\ExpiredPasswordService;
use Application\Listener\ExpiredPasswordListener;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\Date\DateTimeHolder;
use DvsaFeature\Factory\FeatureTogglesFactory;
use DvsaFeature\FeatureToggles;
use Zend\Http\Response;
use Zend\Log\LoggerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceManager;

class ExpiredPasswordListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MotIdentityProviderInterface $identityProvider 
     */
    private $identityProvider;

    /**
     * @var DateTimeHolder $timeHolder
     */
    private $timeHolder;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * @var ExpiredPasswordService $expiredPasswordService
     */
    private $expiredPasswordService;
    
    /**
     * @var ServiceManager $serviceManager
     */
    private $serviceManager;

    /**
     * @var TreeRouteStack $router
     */
    private $router;

    /**
     * @var FeatureToggles $featureToggles
     */
    private $featureToggles;

    public function setUp()
    {
        $this->identityProvider = $this
            ->getMockBuilder(MotIdentityProviderInterface::class)
            ->getMock();
        
        $this->timeHolder = $this
            ->getMockBuilder(DateTimeHolder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->timeHolder
            ->method('getCurrent')
            ->will(
                $this->returnValue(
                    new \DateTime('2016-01-01')
                )
            );
        
        $this->logger = $this
            ->getMockBuilder(LoggerInterface::class)
            ->getMock();
        
        $this->expiredPasswordService = $this
            ->getMockBuilder(ExpiredPasswordService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expiredPasswordService
            ->method('calculatePasswordChangePromptDate')
            ->will($this->returnValue(new \DateTime('2000-01-01')));

        $this->router = $this
            ->getMockBuilder(TreeRouteStack::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Test that the URL that the user is redirected to when their password
     * expires is the correct one.
     *
     * @coversNothing
     */
    public function testChangePasswordLinkIsCorrectNewProfile()
    {
        $this->withNewProfileToggleOn();

        $routeMatch = new RouteMatch([]);
        $routeMatch->setMatchedRouteName('newProfile');

        $expiredPasswordListener = new ExpiredPasswordListener(
            $this->identityProvider,
            $this->timeHolder,
            $this->logger,
            $this->expiredPasswordService,
            $this->featureToggles
        );

        $e = new MvcEvent();
        $e->setName(MvcEvent::EVENT_DISPATCH);
        $e->setResponse(new Response());
        $e->setRouteMatch($routeMatch);
        $e->setRouter($this->router);

        $expiredPasswordListener($e);

        $this->assertEquals(
            Response::STATUS_CODE_200, $e->getResponse()->getStatusCode()
        );
    }

    /**
     * Test that the URL that the user is redirected to when their password
     * expires is the correct one.
     *
     * @coversNothing
     */
    public function testChangePasswordLinkIsCorrectOldProfile()
    {
        $this->withNewProfileToggleOff();

        $routeMatch = new RouteMatch([]);
        $routeMatch->setMatchedRouteName('newProfile');

        $expiredPasswordListener = new ExpiredPasswordListener(
            $this->identityProvider,
            $this->timeHolder,
            $this->logger,
            $this->expiredPasswordService,
            $this->featureToggles
        );

        $e = new MvcEvent();
        $e->setName(MvcEvent::EVENT_DISPATCH);
        $e->setResponse(new Response());
        $e->setRouteMatch($routeMatch);
        $e->setRouter($this->router);

        $expiredPasswordListener($e);

        $this->assertEquals(
            Response::STATUS_CODE_200, $e->getResponse()->getStatusCode()
        );
    }

    /**
     * @return $this
     */
    public function withNewProfileToggleOn()
    {
        $this->withFeatureToggles([FeatureToggle::NEW_PERSON_PROFILE => true]);
        $this->router
            ->method('assemble')
            ->will($this->returnValue('your-profile/change-password'));
        return $this;
    }

    /**
     * @return $this
     */
    public function withNewProfileToggleOff()
    {
        $this->withFeatureToggles([FeatureToggle::NEW_PERSON_PROFILE => false]);
        $this->router
            ->method('assemble')
            ->will($this->returnValue('profile/change-password'));
        return $this;
    }

    /**
     * @param array $featureToggles
     *
     * @return $this
     */
    public function withFeatureToggles(array $featureToggles = [])
    {
        $map = [];
        foreach ($featureToggles as $name => $value) {
            $map[] = [(string) $name, (bool) $value];
        }

        $featureToggles = $this
            ->getMockBuilder(FeatureToggles::class)
            ->disableOriginalConstructor()
            ->getMock();
        $featureToggles
            ->method('isEnabled')
            ->will($this->returnValueMap($map));

         $this->featureToggles = $featureToggles;

        return $this;
    }
}