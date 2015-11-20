<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaMotApiTest\Validation;

use DvsaEntities\Entity\EmergencyLog;
use DvsaMotApi\Service\EmergencyService;
use DvsaMotApi\Validation\ContingencyTestValidator;
use Exception;
use PHPUnit_Framework_TestCase;
use SiteApi\Service\SiteService;
use stdClass;

class ContingencyTestValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var EmergencyService
     */
    private $emergencyService;

    /**
     * @var SiteService
     */
    private $siteService;

    public function setUp()
    {
        $this->emergencyService = $this
            ->getMockBuilder(EmergencyService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->siteService = $this
            ->getMockBuilder(SiteService::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testSiteWithValidSite()
    {
        $this
            ->siteService
            ->expects($this->once())
            ->method('getSite')
            ->willReturn(new stdClass());

        $validator = new ContingencyTestValidator($this->emergencyService, $this->siteService);

        $result = $validator->validate(['siteId' => '123']);
        $messages = $result->getFlattenedMessages();

        $this->assertArrayNotHasKey('site', $messages);
    }

    public function testWithInvalidSite()
    {
        $this
            ->siteService
            ->expects($this->once())
            ->method('getSite')
            ->will($this->throwException(new Exception()));

        $validator = new ContingencyTestValidator($this->emergencyService, $this->siteService);

        $result = $validator->validate(['siteId' => '123']);
        $messages = $result->getFlattenedMessages();
        $this->assertArrayHasKey(ContingencyTestValidator::FIELDSET_SITE, $messages);
        $this->assertNotEmpty($messages[ContingencyTestValidator::FIELDSET_SITE]);
        $this->assertEquals(ContingencyTestValidator::MESSAGE_MUST_BE_VALID_SITE,
            $messages[ContingencyTestValidator::FIELDSET_SITE]);
        $this->assertFalse($result->isValid());
    }

    public function testWithValidContingencyCode()
    {
        $this
            ->emergencyService
            ->expects($this->once())
            ->method('getEmergencyLog')
            ->willReturn(new EmergencyLog());

        $validator = new ContingencyTestValidator($this->emergencyService, $this->siteService);

        $result = $validator->validate(['contingencyCode' => '12345A']);
        $messages = $result->getFlattenedMessages();

        $this->assertArrayNotHasKey(ContingencyTestValidator::FIELDSET_CONTINGENCY_CODE, $messages);
    }

    public function testWithInvalidContingencyCode()
    {
        $this
            ->emergencyService
            ->expects($this->once())
            ->method('getEmergencyLog')
            ->willReturn(null);

        $validator = new ContingencyTestValidator($this->emergencyService, $this->siteService);

        $result = $validator->validate(['contingencyCode' => '12345A']);
        $messages = $result->getFlattenedMessages();
        $this->assertArrayHasKey(ContingencyTestValidator::FIELDSET_CONTINGENCY_CODE, $messages);
        $this->assertNotEmpty($messages[ContingencyTestValidator::FIELDSET_CONTINGENCY_CODE]);
        $this->assertEquals(ContingencyTestValidator::MESSAGE_MUST_BE_VALID_CONTINGENCY_CODE, $messages['contingencyCode']);
        $this->assertFalse($result->isValid());
    }
}
