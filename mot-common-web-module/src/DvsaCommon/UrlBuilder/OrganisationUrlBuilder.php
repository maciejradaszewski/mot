<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Creates url for OrganisationApi
 */
class OrganisationUrlBuilder extends UrlBuilder
{
    const ORGANISATION = 'organisation/:id';
    const POSITION = '/position[/:positionId]';

    const USAGE = '/slot-usage';
    const USAGE_PERIOD_DATA = '/period-data';
    const EVENT = '/event';

    protected $routesStructure
        = [
            self::ORGANISATION => [
                self::EVENT => '',
                self::POSITION => '',
                self::USAGE    => [
                    self::USAGE_PERIOD_DATA => '',
                ],
            ],
        ];

    public static function organisationById($id)
    {
        return (new self())->appendRoutesAndParams(self::ORGANISATION)->routeParam('id', $id);
    }

    public static function position($orgId, $positionId = null)
    {
        return self::organisationById($orgId)
            ->appendRoutesAndParams(self::POSITION)
            ->routeParam('positionId', $positionId);
    }

    public function createEvent()
    {
        return $this->appendRoutesAndParams(self::EVENT);
    }

    public function usage()
    {
        return $this->appendRoutesAndParams(self::USAGE);
    }

    public function periodData()
    {
        return $this->appendRoutesAndParams(self::USAGE_PERIOD_DATA);
    }
}
