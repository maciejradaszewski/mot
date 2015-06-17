<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Url Builder for web for the Events
 */
class EventUrlBuilderWeb extends AbstractUrlBuilder
{
    const MAIN = '/event';
    const EVENT_LIST = '/list/:type/:id';
    const EVENT_DETAIL = '/:type/:id/:event-id';

    protected $routesStructure
        = [
            self::MAIN =>
                [
                    self::EVENT_LIST    => '',
                    self::EVENT_DETAIL  => '',
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

    public function eventDetail($id, $eventId, $type)
    {
        $this->appendRoutesAndParams(self::EVENT_DETAIL);
        $this->routeParam('type', $type);
        $this->routeParam('event-id', $eventId);
        $this->routeParam('id', $id);

        return $this;
    }
}
