<?php
/**
 * Created by PhpStorm.
 * User: szymonf
 * Date: 24.03.2016
 * Time: 11:33
 */

namespace Dvsa\Mot\Frontend\PersonModule\Model;


use Core\TwoStepForm\FormContextInterface;
use Dvsa\Mot\Frontend\PersonModule\Controller\QualificationDetailsController;

class QualificationDetailsContext implements FormContextInterface
{
    private $personId;
    private $group;
    private $controller;

    public function __construct($personId, $group, QualificationDetailsController $controller)
    {

        $this->personId = $personId;
        $this->group = $group;
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return int
     */
    public function getPersonId()
    {
        return $this->personId;
    }

    /**
     * @param int $personId
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;
    }

    /**
     * @return QualificationDetailsController
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param QualificationDetailsController $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }
}