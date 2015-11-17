<?php

namespace DvsaClientTest\Mapper;

use DvsaClient\Mapper\SitePositionMapper;

/**
 * Class SitePositionMapperTest
 *
 * @package DvsaClientTest\Mapper
 */
class SitePositionMapperTest extends AbstractMapperTest
{
    /** @var $mapper SitePositionMapper */
    private $mapper;

    public function setUp()
    {
        parent::setUp();

        $this->mapper = new SitePositionMapper($this->client);
    }

    public function testPost()
    {
        $this->mapper->post(1, 1, 1);
    }

    public function testDelete()
    {
        $this->mapper->deletePosition(1, 1);
    }

    public function testGetList()
    {
        $this->mapper->validate(1, 1, 1);
    }
}
