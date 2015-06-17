<?php

namespace DvsaCommon\UrlBuilder;

class MessageUrlBuilder extends AbstractUrlBuilder
{
    const MESSAGE = 'message';

    protected $routesStructure = [
        self::MESSAGE => '',
    ];

    public static function message()
    {
        $urlBuilder = new self();

        return $urlBuilder->appendRoutesAndParams(self::MESSAGE);
    }
}
