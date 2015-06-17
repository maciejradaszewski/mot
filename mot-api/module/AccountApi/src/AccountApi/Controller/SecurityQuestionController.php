<?php

namespace AccountApi\Controller;

use AccountApi\Service\SecurityQuestionService;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;

/**
 * Class SecurityQuestionController
 * @package AccountApi\Controller
 */
class SecurityQuestionController extends AbstractDvsaRestfulController
{
    /** @var SecurityQuestionService */
    protected $securityQuestionService;

    public function __construct(SecurityQuestionService $securityQuestionService)
    {
        $this->securityQuestionService = $securityQuestionService;
    }

    /**
     * This endpoint is used to retrieve all the question
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        return ApiResponse::jsonOk($this->securityQuestionService->getAll());
    }

    /**
     * This endpoint is used to verify the correctness of an answer to a users
     * security question.
     *
     * It returns 200 if the answer is correct or 401 otherwise.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function verifyAnswerAction()
    {
        $questionId = (int)$this->params()->fromRoute('qid', 0);
        $userId = (int)$this->params()->fromRoute('uid', 0);
        $answer = $this->getRequest()->getQuery('answer', '');

        return ApiResponse::jsonOk($this->securityQuestionService->isAnswerCorrect($questionId, $userId, $answer));
    }


    /**
     * This endpoint is used to obtain a question for a specific user
     * and question index.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getQuestionForPersonAction()
    {
        $questionId = (int)$this->params()->fromRoute('qid', 0);
        $userId = (int)$this->params()->fromRoute('uid', 0);

        return ApiResponse::jsonOk($this->securityQuestionService->findQuestionByQuestionNumber($questionId, $userId));
    }
}
