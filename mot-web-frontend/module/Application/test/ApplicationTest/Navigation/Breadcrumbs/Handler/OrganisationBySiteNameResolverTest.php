<?php

namespace ApplicationTest\Navigation\Breadcrumbs\Handler;

use Application\Navigation\Breadcrumbs\Handler\OrganisationNameBySiteResolver;
use DvsaCommon\HttpRestJson\Client;
use DvsaCommonTest\TestUtils\XMock;
use Zend\View\Helper\Url;

class OrganisationBySiteNameResolverTest extends \PHPUnit_Framework_TestCase
{
    private $urlHelper;
    private $resolver;
    private $client;

    public function setUp()
    {
        $this->urlHelper = XMock::of(Url::class);
        $this->client = XMock::of(Client::class);
        $this->resolver = new OrganisationNameBySiteResolver($this->client, $this->urlHelper);
    }

    public function testResolve_whenOrganisationFoundForSite_shouldReturnProperLink()
    {
        $siteId = 4;
        $organisationId = 5;
        $this->urlHelper->expects($this->once())->method('__invoke')
            ->with('authorised-examiner', ['id' => $organisationId])->willReturn('url');
        $this->client->expects($this->once())->method('get')
            ->willReturn(['data' => ['id' => $organisationId, 'name' => 'OrganisationName']]);

        $result = $this->resolver->resolve($siteId);

        $this->assertEquals(['OrganisationName' => 'url'], $result);
    }

    public function testResolve_whenOrganisationNotFoundForSite_shouldReturnEmptyLink()
    {
        $siteId = 4;
        $this->urlHelper->expects($this->never())->method('__invoke');
        $this->client->expects($this->once())->method('get')
            ->willReturn(['data' => []]);

        $result = $this->resolver->resolve($siteId);

        $this->assertEquals([], $result);
    }
}
