<?php

namespace PersonApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use PersonApi\Generator\PersonGenerator;
use PersonApi\Service\PersonService;
use Zend\View\Model\JsonModel;

/**
 * Class PersonController.
 */
class PersonController extends AbstractDvsaRestfulController
{
    /**
     * @var PersonService
     */
    protected $personService;

    /**
     * @var PersonGenerator
     */
    protected $personGenerator;

    public function __construct(PersonService $personService, PersonGenerator $personGenerator)
    {
        $this->personService   = $personService;
        $this->personGenerator = $personGenerator;
    }

    /**
     * @param mixed $personId
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function get($personId)
    {
        $data     = $this->personService->getPerson($personId);
        $response = $this->personGenerator->getPerson($data);

        return new JsonModel($response);
    }
}
