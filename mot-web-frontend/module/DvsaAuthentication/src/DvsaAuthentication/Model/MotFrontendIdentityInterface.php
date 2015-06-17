<?php

namespace DvsaAuthentication\Model;

use DvsaCommon\Auth\MotIdentityInterface;

/**
 * Interface MotFrontendIdentityInterface
 */
interface MotFrontendIdentityInterface extends MotIdentityInterface
{
    public function getDisplayName();

    /**
     * @return \DvsaAuthentication\Model\VehicleTestingStation
     */
    public function getCurrentVts();
}
