<?php

namespace Account\AbstractClass;

use Account\Service\SecurityQuestionService;
use DvsaCommon\Dto\Security\SecurityQuestionDto;
use DvsaCommon\UrlBuilder\UrlBuilderWeb;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Model\ViewModel;
use DvsaClient\Entity\Person;

/**
 * Class AbstractSecurityQuestionViewModel
 * @package Account\AbstractClass
 */
abstract class AbstractSecurityQuestionViewModel
{
    /** @var SecurityQuestionService */
    protected $service;

    /**
     * @param FlashMessenger $flashMessenger
     * @return UrlBuilderWeb
     */
    abstract public function getNextPageLink(FlashMessenger $flashMessenger);

    /**
     * @return UrlBuilderWeb
     */
    abstract public function getCurrentLink();

    /**
     * @param SecurityQuestionService $service
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * @return SecurityQuestionDto
     */
    public function getQuestion()
    {
        return $this->service->getQuestion();
    }

    /**
     * @return int
     */
    public function getQuestionNumber()
    {
        return $this->service->getQuestionNumber();
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->service->getUserId();
    }


    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->service->getPerson();
    }

    /**
     * @return string
     */
    public function getSearchParams()
    {
        return $this->service->getSearchParams();
    }
}
