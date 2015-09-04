<?php

namespace DvsaCommon\UrlBuilder;

class RegistrationUrlBuilder extends AbstractUrlBuilder
{
    const MAIN = 'account/register';

    /**
     * Keys define the route, we need to set the value of each because of the way AbstractUrlBuilder checks
     * the routing when converting back to a string
     * @see AbstractUrlBuilder::verifyOrderOfRoute
     * @var array
     */
    protected $routesStructure = [
        self::MAIN => ''
    ];

    /**
     * @return $this
     */
    public function register()
    {
        $this->appendRoutesAndParams(self::MAIN);
        return $this;
    }
}