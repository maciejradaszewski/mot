<?php

namespace Dvsa\Mot\Behat\Datasource;

use Faker;

class Random
{
    /**
     * @return string
     */
    public static function getRandomEmail()
    {
        return Faker\Factory::create()->email;
    }

    /**
     * @return string
     */
    public static function password()
    {
        return 'Password1'.rand(0, 1000);
    }
}
