<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Service;

use DvsaClient\MapperFactory;
use Zend\Session\Container;

/**
 * Class RegistrationSessionService.
 */
class RegistrationSessionService
{
    const UNIQUE_KEY = 'registration';

    /**
     * @var Container
     */
    protected $sessionContainer;

    /**
     * @var MapperFactory
     */
    private $mapper;

    /**
     * @param Container     $sessionContainer
     * @param MapperFactory $mapper
     */
    public function __construct(Container $sessionContainer, MapperFactory $mapper)
    {
        $this->sessionContainer = $sessionContainer;
        $this->mapper = $mapper;
    }
    /**
     * Clear and Kill the Session.
     */
    public function destroy()
    {
        $this->sessionContainer->getManager()->destroy();
    }

    /**
     * Clear all the data from sessionStorage.
     */
    public function clear()
    {
        /** @var \Zend\Session\Storage\StorageInterface $storage */
        $storage = $this->sessionContainer->getManager()->getStorage();

        $storage->clear(self::UNIQUE_KEY);
    }

    /**
     * @param $key
     *
     * @return array|mixed
     */
    public function load($key)
    {
        if ($this->sessionContainer->offsetExists($key)) {
            return $this->sessionContainer->offsetGet($key);
        }

        return [];
    }

    /**
     * @param $key
     * @param $value
     */
    public function save($key, $value)
    {
        $this->sessionContainer->offsetSet($key, $value);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->sessionContainer->getArrayCopy();
    }

    /**
     *
     */
    public function checkQuestionsAvailable()
    {
        $groupA = 'securityQuestionsGroupA';
        $groupB = 'securityQuestionsGroupB';

        if (!($this->sessionContainer->offsetExists($groupA) && ($this->sessionContainer->offsetExists($groupB)))) {
            $questions = $this->getSecurityQuestions();
            $this->save($groupA, $questions['groupA']);
            $this->save($groupB, $questions['groupB']);
        }
    }

    /**
     * @return array
     */
    public function getSecurityQuestions()
    {
        $questionSet = $this->mapper->SecurityQuestion->fetchAllGroupedAndOrdered();

        return [
            'groupA' => $this->getQuestionForGroup($questionSet->getGroupOne()),
            'groupB' => $this->getQuestionForGroup($questionSet->getGroupTwo()),
        ];
    }

    /**
     * @param $questions
     *
     * @return array
     */
    private function getQuestionForGroup($questions)
    {
        $result = [];
        /** @var \DvsaCommon\Dto\Security\SecurityQuestionDto $question */
        foreach ($questions as $question) {
            $result[$question->getId()] = $question->getText();
        }

        return $result;
    }
}
