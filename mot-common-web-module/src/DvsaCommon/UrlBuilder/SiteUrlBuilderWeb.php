<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Url Builder for web for Site
 */
class SiteUrlBuilderWeb extends AbstractUrlBuilder
{
    const MAIN = '/vehicle-testing-station[/:id]';
    const REMOVE_ROLE = '/remove-role/:positionId';
    const SEARCH = '/search';
    const RESULT = '/result';

    protected $routesStructure
        = [
            self::MAIN => [
                self::REMOVE_ROLE => '',
                self::SEARCH => '',
                self::RESULT => '',
            ],
        ];

    public function __construct($id = null)
    {
        $this->appendRoutesAndParams(self::MAIN);

        if ($id !== null) {
            $this->routeParam('id', $id);
        }

        return $this;
    }

    public static function of($id = null)
    {
        return new static($id);
    }

    public static function search()
    {
        return (new static())->appendRoutesAndParams(self::SEARCH);
    }

    public static function result()
    {
        return (new static())->appendRoutesAndParams(self::RESULT);
    }

    public static function removeRole($siteId, $positionId)
    {
        return (new static($siteId))->appendRoutesAndParams(self::REMOVE_ROLE)->routeParam('positionId', $positionId);
    }
}
