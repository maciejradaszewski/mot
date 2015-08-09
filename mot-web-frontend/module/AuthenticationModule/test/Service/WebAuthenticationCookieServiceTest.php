<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\Service;

use Dvsa\OpenAM\Options\OpenAMClientOptions;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebAuthenticationCookieService;
use DvsaCommon\Utility\ArrayUtils;
use Zend\Http\Header\Cookie;
use Zend\Http\Header\SetCookie;
use Zend\Http\Request;
use Zend\Http\Response;

class WebAuthenticationCookieServiceTest extends \PHPUnit_Framework_TestCase
{
    const COOKIE_NAME = 'iPlanetDirectoryPro';
    const COOKIE_VALUE = '43904309f90cr09f934';
    const COOKIE_DOMAIN = 'myDomain.com';
    const COOKIE_HTTPONLY = true;
    const COOKIE_SECURE = true;
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var OpenAMClientOptions
     */
    private $openAMOptions;

    public function setUp()
    {
        $this->request = new Request();

        $this->response = new Response();
        $this->openAMOptions = new OpenAMClientOptions();
        $this->openAMOptions->setCookieName(self::COOKIE_NAME);
        $this->openAMOptions->setCookieDomain(self::COOKIE_DOMAIN);
        $this->openAMOptions->setCookieHttpOnly(self::COOKIE_HTTPONLY);
        $this->openAMOptions->setCookieSecure(self::COOKIE_SECURE);
    }

    public function testGetToken_givenTokenOnRequest_returnIt()
    {
        $this->request->getHeaders()->addHeader(
            Cookie::fromSetCookieArray([new SetCookie(self::COOKIE_NAME, self::COOKIE_VALUE)]));
        $this->assertEquals(self::COOKIE_VALUE, $this->createService()->getToken());
    }

    public function testGetToken_givenNoTokenOnRequest_returnNull()
    {
        $this->assertNull($this->createService()->getToken());
    }

    public function testSetUpCookie()
    {
        $this->createService()->setUpCookie(self::COOKIE_VALUE);
        $actualCookie = $this->findCookie($this->response->getCookie(), self::COOKIE_NAME);
        $this->assertEquals(self::COOKIE_DOMAIN, $actualCookie->getDomain());
        $this->assertEquals(self::COOKIE_SECURE, $actualCookie->isSecure());
        $this->assertEquals(self::COOKIE_HTTPONLY, $actualCookie->isHttponly());
        $this->assertTrue($actualCookie->isSessionCookie());
        $this->assertEquals(self::COOKIE_VALUE, $actualCookie->getValue());
    }

    public function testTearDownCookie()
    {
        $this->createService()->tearDownCookie();
        $actualCookie = $this->findCookie($this->response->getCookie(), self::COOKIE_NAME);
        $this->assertEquals(self::COOKIE_DOMAIN, $actualCookie->getDomain());
        $this->assertEquals(self::COOKIE_SECURE, $actualCookie->isSecure());
        $this->assertEquals(self::COOKIE_HTTPONLY, $actualCookie->isHttponly());
        $this->assertFalse($actualCookie->isSessionCookie());
        $this->assertNull($actualCookie->getValue());
        $this->assertNotNull($actualCookie->getExpires(true));
    }

    /**
     * @return SetCookie
     */
    private function findCookie($cookies, $name)
    {
        return ArrayUtils::firstOrNull($cookies, function (SetCookie $cookie) use ($name) {
            return $cookie->getName() === $name;
        });
    }

    /**
     * @return WebAuthenticationCookieService
     */
    private function createService()
    {
        return new WebAuthenticationCookieService($this->request, $this->response, $this->openAMOptions);
    }
}
