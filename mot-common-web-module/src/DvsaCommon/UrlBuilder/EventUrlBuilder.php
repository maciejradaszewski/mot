<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Url Builder for the Events Api Call
 */
class EventUrlBuilder extends AbstractUrlBuilder
{
    const MAIN = 'event';
    const EVENT_LIST = '/list/:type/:id';
    const EVENT = '/:id';

    protected $routesStructure
        = [
            self::MAIN =>
                [
                    self::EVENT_LIST    => '',
                    self::EVENT         => '',
                ],
        ];

    public function __construct()
    {
        $this->appendRoutesAndParams(self::MAIN);
        return $this;
    }

    public static function of()
    {
        return new static();
    }

    public function eventList($id, $type)
    {
        $this->appendRoutesAndParams(self::EVENT_LIST);
        $this->routeParam('type', $type);
        $this->routeParam('id', $id);

        return $this;
    }

    public function event($id)
    {
        $this->appendRoutesAndParams(self::EVENT);
        $this->routeParam('id', $id);

        return $this;
    }
}
