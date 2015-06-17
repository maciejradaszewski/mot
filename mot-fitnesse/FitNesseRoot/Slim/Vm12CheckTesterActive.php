<?php

require_once 'configure_autoload.php';

use DvsaCommon\Constants\Role;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Testing if given user is an active tester
 */
class Vm12CheckTesterActive extends Vm12AndVm13Base
{
    protected function retrieveData()
    {
        return TestShared::execCurlForJsonFromUrlBuilder(
            $this,
            (new UrlBuilder())->user()->routeParam('id', $this->username)
        );
    }

    public function active()
    {
        $jsonResult = $this->retrieveData();

        return in_array(Role::TESTER_ACTIVE, $jsonResult['data']['roles']) ? 'ACTIVE' : 'INACTIVE';
    }

    public function setUserInfo($value)
    {
    }
}
