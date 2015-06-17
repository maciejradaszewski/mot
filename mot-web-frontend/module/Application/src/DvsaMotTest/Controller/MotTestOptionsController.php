<?php

namespace DvsaMotTest\Controller;

use DvsaCommon\Dto\MotTesting\MotTestOptionsDto;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaMotTest\Presenter\MotTestOptionsPresenter;
use Zend\View\Model\ViewModel;

class MotTestOptionsController extends AbstractDvsaMotTestController
{
    const ROUTE_MOT_TEST_OPTIONS = 'mot-test/options';

    const TEMPLATE_MOT_TEST_OPTIONS = 'dvsa-mot-test/mot-test/mot-test-options.phtml';

    const PAGE_TITLE = 'MOT test started';
    const PAGE_SUB_TITLE = 'MOT testing';

    public function motTestOptionsAction()
    {
        $motTestNumber = $this->params()->fromRoute('motTestNumber');

        $dto = MotTestOptionsDto::fromArray(
            $this->getRestClient()->get(UrlBuilder::motTestOptions($motTestNumber)->toString())['data']
        );

        $this->layout()->setVariable('pageTitle', self::PAGE_TITLE);
        $this->layout()->setVariable('pageSubTitle', self::PAGE_SUB_TITLE);

        $viewModel = new ViewModel(['presenter' => new MotTestOptionsPresenter($dto)]);
        $viewModel->setTemplate(self::TEMPLATE_MOT_TEST_OPTIONS);

        return $viewModel;
    }
}
