<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Event\ViewModel\Event;

use DvsaClient\Entity\Person;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Dto\Event\EventFormDto;
use DvsaCommon\Dto\Event\EventListDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\UrlBuilder\EventUrlBuilderWeb;
use DvsaCommon\UrlBuilder\SiteUrlBuilderWeb;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use DvsaFeature\FeatureToggles;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class EventViewModel.
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
    /**
     * @var bool featureToggles
     */
    private $newProfileEnabled;

    /* @var string $eventType */
    private $eventType;

    /**
     * @param OrganisationDto          $organisation
     * @param VehicleTestingStationDto $site
     * @param Person                   $person
     * @param EventFormDto             $formModel
     * @param string                   $eventType
     * @param int                      $id
     * @param bool                     $newProfileEnabled
     */
    public function __construct(
        $organisation,
        $site,
        $person,
        EventFormDto $formModel,
        $eventType,
        $id,
        $newProfileEnabled
    ) {
        $this->setOrganisation($organisation);
        $this->setSite($site);
        $this->setPerson($person);
        $this->setFormModel($formModel);
        $this->setEventType($eventType);
        $this->setId($id);
        $this->newProfileEnabled = $newProfileEnabled;
    }

    /**
     * This function return the good value for the link to the event detail.
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
     * This function return the good value for the go back link of the Event list.
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
                return $this->newProfileEnabled ? '/preview/profile/' . $this->person->getId() : UserAdminUrlBuilderWeb::userProfile($this->person->getId());
        }

        return '';
    }

    /**
     * This function return the good value for the all link.
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
     * in function of witch entity we want the list.
     *
     * @return string
     */
    public function getTitle()
    {
        switch ($this->eventType) {
            case 'ae':
                return sprintf(
                    '%s - %s',
                    $this->organisation->getAuthorisedExaminerAuthorisation()->getAuthorisedExaminerRef(),
                    $this->organisation->getName()
                );
            case 'site':
                return sprintf(
                    '%s - %s',
                    $this->site->getSiteNumber(),
                    $this->site->getName()
                );
            case 'person':
                return sprintf(
                    '%s',
                    $this->person->getFullName()
                );
        }

        return;
    }

    public function parseEventForJson()
    {
        $result = [];
        foreach ($this->getEventList()->getEvents() as $event) {

            $finalDescription = '';
            $tempDescription = $event->getDescription();
            $tempOutcome = $event->getEventOutcomeDescription();

            if(!empty($tempOutcome)) {

                $finalDescription = $tempOutcome . '. ' . $tempDescription;

            } else {

                $finalDescription =$tempDescription;
            }

            $result[] = [
                'type' => [
                    'type' => $event->getType(),
                    'url'  => $this->getEventDetailLink($event->getId())->toString() . '?' .
                        http_build_query($this->formModel->toArray()),
                ],
                'date'        => DateTimeDisplayFormat::textDateTimeShort($event->getDate()),
                'description' => $finalDescription,
            ];
        }

        return $result;
    }

    /**
     * @return ViewModel
     */
    public function getViewModel()
    {
        return (new ViewModel(
            [
                'viewModel' => $this,
            ]
        ))->setTemplate('event/event/index.phtml');
    }

    /**
     * @return JsonModel
     */
    public function getJsonModel()
    {
        return new JsonModel(
            [
                'data'                 => $this->parseEventForJson(),
                'iTotalDisplayRecords' => $this->getEventList()->getTotalResult(),
                'iTotalRecords'        => $this->getEventList()->getTotalResult(),
                'sEcho'                => $this->getFormModel()->getPageNumber(),
            ]
        );
    }

    /**
     * @param bool|false $isJson
     *
     * @return JsonModel|ViewModel
     */
    public function getViewOrJson($isJson = false)
    {
        return ($isJson === true) ? $this->getJsonModel() : $this->getViewModel();
    }

    /**
     * @param OrganisationDto $organisation
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
