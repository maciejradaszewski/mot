<?php

namespace DvsaClientTest\Mapper;

use DvsaClient\Mapper\OrganisationMapper;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\HttpRestJson\Client;

/**
 * Class OrganisationMapperTest
 *
 * @package DvsaClientTest\Mapper
 */
class OrganisationMapperTest extends \PHPUnit_Framework_TestCase
{
    const AE_ID = 1;
    const AE_NUMBER = 'A-12345';

    /**
     * @var $mapper OrganisationMapper
     */
    private $mapper;

    /** @var $client \PHPUnit_Framework_MockObject_MockBuilder */
    private $client;

    public function setUp()
    {
        $this->client = \DvsaCommonTest\TestUtils\XMock::of(Client::class, ['get', 'getWithParams']);
        $this->mapper = new OrganisationMapper($this->client);
    }

    public function testFetchAllForManager()
    {
        $this->client->expects($this->any())
            ->method('get')
            ->willReturn(['data' => ['_class' => 'DvsaCommon\\Dto\\Organisation\\OrganisationDto']]);
        $this->assertInstanceOf(
            OrganisationDto::class,
            $this->mapper->fetchAllForManager(self::AE_ID)
        );
    }

    public function testGetAuthorisedExaminer()
    {
        $this->client->expects($this->any())
            ->method('get')
            ->willReturn(['data' => ['_class' => 'DvsaCommon\\Dto\\Organisation\\OrganisationDto']]);
        $this->assertInstanceOf(
            OrganisationDto::class,
            $this->mapper->getAuthorisedExaminer(self::AE_ID)
        );
    }

    public function testGetAuthorisedExaminerByNumber()
    {
        $this->client->expects($this->any())
            ->method('getWithParams')
            ->willReturn(['data' => ['_class' => 'DvsaCommon\\Dto\\Organisation\\OrganisationDto']]);
        $this->assertInstanceOf(
            OrganisationDto::class,
            $this->mapper->getAuthorisedExaminerByNumber(self::AE_NUMBER)
        );
    }
}
