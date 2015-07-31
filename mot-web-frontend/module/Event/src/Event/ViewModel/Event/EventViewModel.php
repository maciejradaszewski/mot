<?php

namespace Event\ViewModel\Event;

use DvsaClient\Entity\Person;
use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Dto\Event\EventFormDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\UrlBuilder\EventUrlBuilderWeb;
use DvsaCommon\UrlBuilder\SiteUrlBuilderWeb;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Dto\Event\EventListDto;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class EventViewModel
 * @package Event\ViewModel\Event
 */
class EventViewModel
{
    /** @var int */
    private $id;
    /** @var OrganisationDto */
    private $organisation;
    /** @var VehicleTestingStationDto */
    private $site;
    /** @var Person */
    private $person;
    /** @var EventFormDto */
    private $formModel;
    /** @var EventListDto list of extracted Dto events prepared for list */
    private $eventList;

    /* @var string $eventType */
    private $eventType;

    /**
     * @param OrganisationDto           $organisation
     * @param VehicleTestingStationDto  $site
     * @param Person                    $person
     * @param EventFormDto              $formModel
     * @param string                    $eventType
     * @param int                       $id
     */
    public function __construct(
        $organisation,
        $site,
        $person,
        EventFormDto $formModel,
        $eventType,
        $id
    ) {
        $this->setOrganisation($organisation);
        $this->setSite($site);
        $this->setPerson($person);
        $this->setFormModel($formModel);
        $this->setEventType($eventType);
        $this->setId($id);
    }

    /**
     * This function return the good value for the link to the event detail
     *
     * @param int $eventId
     *
     * @return string
     */
    public function getEventDetailLink($eventId)
    {
        return EventUrlBuilderWeb::of()->eventDetail($this->getId(), $eventId, $this->getEventType());
    }

    /**
     * This function return the good value for the go back link of the Event list
     *
     * @return string
     */
    public function getGoBackLink()
    {
        switch ($this->eventType) {
            case 'ae':
                return AuthorisedExaminerUrlBuilderWeb::of($this->organisation->getId());
            case 'site':
                return SiteUrlBuilderWeb::of($this->site->getId());
            case 'person':
                return UserAdminUrlBuilderWeb::userProfile($this->person->getId());
        }
        return '';
    }

    /**
     * This function return the good value for the all link
     *
     * @return string
     */
    public function getCurrentPage()
    {
        switch ($this->eventType) {
            case 'ae':
                return EventUrlBuilderWeb::of()->eventList($this->organisation->getId(), $this->getEventType());
            case 'site':
                return EventUrlBuilderWeb::of()
                    ->eventList($this->site->getId(), $this->getEventType());
            case 'person':
                return EventUrlBuilderWeb::of()->eventList($this->person->getId(), $this->getEventType());
        }
        return '';
    }

    /**
     * This function return the good title of the Event list page
     * in function of witch entity we want the list
     *
     * @return string
     */
    public function getTitle()
    {
        switch ($this->eventType) {
            case 'ae':
                return sprintf(
                    'List of AE events found for organisation "%s - %s"',
                    $this->organisation->getAuthorisedExaminerAuthorisation()->getAuthorisedExaminerRef(),
                    $this->organisation->getName()
                );
            case 'site':
                return sprintf(
                    'List of Site events found for site "%s - %s"',
                    $this->site->getSiteNumber(),
                    $this->site->getName()
                );
            case 'person':
                return sprintf(
                    'List of Person events found for user "%s - %s"',
                    $this->person->getUsername(),
                    $this->person->getFullName()
                );
        }
        return null;
    }

    public function parseEventForJson()
    {
        $result = [];
        foreach ($this->getEventList()->getEvents() as $event) {
            $result[] = [
                'type' => [
                    'type' => $event->getType(),
                    'url' => $this->getEventDetailLink($event->getId())->toString() . '?' .
                        http_build_query($this->formModel->toArray()),
                ],
                'date' => DateTimeDisplayFormat::textDateTimeShort($event->getDate()),
                'description' => $event->getDescription(),
            ];
        }
        return $result;
    }

    public function getViewOrJson($isJson = false)
    {
        if ($isJson === true) {
            return new JsonModel(
                [
                    'data' => $this->parseEventForJson(),
                    'iTotalDisplayRecords' => $this->getEventList()->getTotalResult(),
                    'iTotalRecords' => $this->getEventList()->getTotalResult(),
                    'sEcho' => $this->getFormModel()->getPageNumber()
                ]
            );
        } else {
            return (new ViewModel(
                [
                    'viewModel' => $this
                ]
            ))->setTemplate('event/event/index.phtml');
        }
    }

    /**
     * @param OrganisationDto $organisation
     * @return $this
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
        return $this;
    }

    /**
     * @return OrganisationDto
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @return VehicleTestingStationDto
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param VehicleTestingStationDto $site
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person $person
     * @return $this
     */
    public function setPerson($person)
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return EventFormDto
     */
    public function getFormModel()
    {
        return $this->formModel;
    }

    /**
     * @param EventFormDto $formModel
     * @return $this
     */
    public function setFormModel($formModel)
    {
        $this->formModel = $formModel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @param mixed $eventType
     * @return $this
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
        return $this;
    }

    /**
     * @return EventListDto
     */
    public function getEventList()
    {
        return $this->eventList;
    }

    /**
     * @param EventListDto $eventList
     * @return $this
     */
    public function setEventList($eventList)
    {
        $this->eventList = $eventList;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}
