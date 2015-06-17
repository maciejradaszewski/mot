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

    const SITES = '/site';

    protected $routesStructure
        = [
            self::ORGANISATION => [
                self::POSITION => '',
                self::USAGE    => [
                    self::USAGE_PERIOD_DATA => '',
                ],
                self::SITES    => '',
            ],
        ];

    public static function organisationById($id)
    {
        return (new self())->appendRoutesAndParams(self::ORGANISATION)->routeParam('id', $id);
    }

    public function position()
    {
        return $this->appendRoutesAndParams(self::POSITION);
    }

    public function usage()
    {
        return $this->appendRoutesAndParams(self::USAGE);
    }

    public function periodData()
    {
        return $this->appendRoutesAndParams(self::USAGE_PERIOD_DATA);
    }

    /**
     * @param integer $orgId
     *
     * @return $this
     */
    public static function sites($orgId)
    {
        return self::organisationById($orgId)->appendRoutesAndParams(self::SITES);
    }
}
