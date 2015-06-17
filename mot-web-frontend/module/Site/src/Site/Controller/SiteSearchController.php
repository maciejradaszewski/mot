<?php

namespace Site\Controller;

use DvsaClient\MapperFactory;
use DvsaCommon\Dto\Site\SiteListDto;
use DvsaCommon\UrlBuilder\SiteUrlBuilderWeb;
use DvsaCommon\Utility\DtoHydrator;
use DvsaMotTest\Controller\AbstractDvsaMotTestController;
use Site\ViewModel\SiteSearchViewModel;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;

/**
 * Class SiteSearchController
 * @package Site\Controller
 */
class SiteSearchController extends AbstractDvsaMotTestController
{
    const NO_RESULT = 0;
    const ONE_RESULT = 1;

    const NO_RESULT_FOUND = 'Unable to find any matches. Try expanding your search criteria ';

    const PAGE_TITLE_SEARCH = 'Search for site information by...';
    const PAGE_TITLE_RESULT = 'Search Results';

    const SITE_SEARCH_TEMPLATE = 'site/site-search/search';

    /** @var SiteSearchViewModel */
    private $viewModel;
    /** @var MapperFactory */
    protected $mapper;

    /**
     * @param MapperFactory $mapper
     */
    public function __construct(MapperFactory $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * This action is the end point to search for a site
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function searchAction()
    {
        $this->viewModel = new SiteSearchViewModel();

        return $this->initViewModelInformation(self::PAGE_TITLE_SEARCH);
    }

    /**
     * This action is the end point to the result search of sites
     * If there is no result we display back the search page
     * If there is only one result we redirect to the detail page
     *
     * @return Response|ViewModel
     */
    public function resultAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost() === false) {
            return $this->redirect()->toUrl(SiteUrlBuilderWeb::search());
        }

        $this->viewModel = (new SiteSearchViewModel())
            ->populateFromPost($request->getPost()->toArray());

        if ($this->viewModel->isValid()) {
            return $this->getResultFromApi();
        }

        return $this->initViewModelInformation(self::PAGE_TITLE_SEARCH)
            ->setTemplate(self::SITE_SEARCH_TEMPLATE);
    }

    private function getResultFromApi()
    {
        try {
            /** @var SiteListDto $result */
            $result = $this->mapper->VehicleTestingStation->search(
                DtoHydrator::dtoToJson($this->viewModel->prepareSearchParams())
            );
        } catch (\Exception $e) {
            $this->addErrorMessage(self::NO_RESULT_FOUND);
            return $this->initViewModelInformation(self::PAGE_TITLE_SEARCH)
                ->setTemplate(self::SITE_SEARCH_TEMPLATE);
        }

        if (self::NO_RESULT === (int)$result->getTotalResult()) {
            $this->addErrorMessage(self::NO_RESULT_FOUND);
            return $this->initViewModelInformation(self::PAGE_TITLE_SEARCH)
                ->setTemplate(self::SITE_SEARCH_TEMPLATE);
        }
        if (self::ONE_RESULT === (int)$result->getTotalResult()) {
            return $this->redirect()->toUrl(SiteUrlBuilderWeb::of($result->getSites()[0]->getId()));
        }
        $this->viewModel->setSiteList($result);
        return $this->initViewModelInformation(self::PAGE_TITLE_RESULT);
    }

    /**
     * This function initialise the view model
     *
     * @param string $title
     * @return ViewModel
     */
    private function initViewModelInformation($title)
    {
        $this->layout('layout/layout-govuk.phtml');

        $this->layout()->setVariable('pageTitle', $title);
        $this->layout()->setVariable('pageSubTitle', 'Site search');

        $breadcrumbs = [
            'Site Information' => '',
        ];
        $this->layout()->setVariable('progressBar', ['breadcrumbs' => $breadcrumbs]);

        return new ViewModel(
            [
                'viewModel' => $this->viewModel,
            ]
        );
    }
}
