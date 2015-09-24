<?php

namespace Dvsa\Mot\Behat\Support\Api;

class Event extends MotApi
{
    const PATH_DETAILS = 'event/{id}';
    const PATH_PERSON_LIST = 'event/list/person/{personId}';
    const PATH_SITE_LIST = 'event/list/site/{siteId}';
    const PATH_ORGANISATION_LIST = 'event/list/ae/{organisationId}';

    private $params = [
        "dateFrom" => [
            "date" => null,
            "day" => null,
            "month" => null,
            "year" => null,
            "_class" => "DvsaCommon\\Dto\\Common\\DateDto"
        ],
        "dateTo" => [
            "date" => null,
            "day" => null,
            "month" => null,
            "year" => null,
            "_class" => "DvsaCommon\\Dto\\Common\\DateDto"
        ],
        "search" => "",
        "isShowDate" => false,
        "pageNumber" => null,
        "displayStart" => 0,
        "displayLength" => 10,
        "sortCol" => 1,
        "sortDir" => "DESC",
        "_class" => "DvsaCommon\\Dto\\Event\\EventFormDto"
    ];

    public function getPersonEventsData($token, $personId)
    {
        $path = str_replace("{personId}", $personId, self::PATH_PERSON_LIST);

        return $this->sendRequest(
            $token,
            MotApi::METHOD_POST,
            $path,
            $this->params
        );
    }

    public function getSiteEventsData($token, $siteId)
    {
        $path = str_replace("{siteId}", $siteId, self::PATH_SITE_LIST);

        return $this->sendRequest(
            $token,
            MotApi::METHOD_POST,
            $path,
            $this->params
        );
    }

    public function getOrganisationEventsData($token, $organisationId)
    {
        $path = str_replace("{organisationId}", $organisationId, self::PATH_ORGANISATION_LIST);

        return $this->sendRequest(
            $token,
            MotApi::METHOD_POST,
            $path,
            $this->params
        );
    }

    public function getEventDetails($token, $eventId)
    {
        $path = str_replace("{id}", $eventId, self::PATH_DETAILS);
        return $this->sendRequest(
            $token,
            MotApi::METHOD_GET,
            $path
        );
    }
    
}