<?php

namespace MotFitnesse\Util;

class OrganisationUrlBuilder extends AbstractUrlBuilder
{
    const ORGANISATION = '/organisation/:id';
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

    public static function slotUsage($orgId)
    {
        return self::organisationById($orgId)->appendRoutesAndParams(self::USAGE);
    }

    public static function slotUsagePeriodData($orgId)
    {
        return self::slotUsage($orgId)->appendRoutesAndParams(self::USAGE_PERIOD_DATA);
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
