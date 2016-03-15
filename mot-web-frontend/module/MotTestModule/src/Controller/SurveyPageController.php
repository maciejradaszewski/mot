<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\MotTestModule\Controller;

use Core\Controller\AbstractAuthActionController;
use Dvsa\Mot\Frontend\MotTestModule\Service\SurveyService;
use DvsaCommon\Constants\FeatureToggle;
use Zend\View\Model\ViewModel;

/**
 * Class SurveyPageController
 * @package DvsaMotTest\Controller
 */
class SurveyPageController extends AbstractAuthActionController
{
    /**
     * @var SurveyService $surveyService
     */
    private $surveyService;

    const VERY_SATISFIED = 5;
    const SATISFIED = 4;
    const NEITHER_SATISFIED_NOR_DISSATISFIED = 3;
    const DISSATISFIED = 2;
    const VERY_DISSATISFIED = 1;

    const SATISFACTION_RATING = 'satisfactionRating';

    /**
     * SurveyPageController constructor.
     * @param SurveyService $surveyService
     */
    public function __construct(
        SurveyService $surveyService
    ) {
        $this->surveyService = $surveyService;
    }

    /**
     * @return array|\Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        $ref = $_SERVER['HTTP_REFERER'];
//        if (strpos($ref, 'test-result') === FALSE && strpos($ref, 'survey') === FALSE) {
//            return $this->notFoundAction();
//        }
        if (true !== $this->isFeatureEnabled(FeatureToggle::SURVEY_PAGE)) {
            return $this->notFoundAction();
        }
        
        $this->layout('layout/layout-govuk.phtml');

        if ($this->getRequest()->isPost()) {
            $satisfactionRating = $this->getRequest()->getPost(self::SATISFACTION_RATING);

            $this->surveyService->submitSurveyResult(
                $satisfactionRating
            );

            if (is_null($satisfactionRating)) {
                return $this->redirect()->toUrl('/');
            }
        }
        
        return $this->createViewModel(
            'survey-page/index.phtml',
            []
        );
    }

    /**
     * @param string $template
     * @param array  $variables
     *
     * @return ViewModel
     */
    private function createViewModel($template, array $variables)
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate($template);
        $viewModel->setVariables($variables);

        return $viewModel;
    }
}