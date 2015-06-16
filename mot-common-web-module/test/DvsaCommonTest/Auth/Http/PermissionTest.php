<?php

namespace DvsaCommonTest\Auth\Http;

use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Utility\ArrayUtils;

class PermissionTest extends \PHPUnit_Framework_TestCase
{
    public function testPermissionShouldNotExistsInMoreThanOneLevel()
    {
        $repeated = [
            'system_site'         => array_intersect(PermissionInSystem::all(), PermissionAtSite::all()),
            'system_organisation' => array_intersect(PermissionInSystem::all(), PermissionAtOrganisation::all()),
            'site_organisation'   => array_intersect(PermissionAtSite::all(), PermissionAtOrganisation::all()),
        ];

        // !!!!!!! DO NOT ADD TO THIS LIST, ASK WITOLD KACPERSKI OR PRZEMYSŁAW SEMPRUCH!!!!!!!!!!
        // IF YOU NEED TO ADD, THEN YOU"RE DOING SOMETHING WRONG
        // AND POTENTIALLY INTRODUCING HOLES TO THE SECURITY SYSTEM
        // TODO: allow these repetitions temporarily. DO NOT add more to this list.
        $repeated ['system_organisation'] = ArrayUtils::filter($repeated['system_organisation'],
            function ($element)
            {
                // !!!!!!! DO NOT ADD TO THIS LIST, ASK WITOLD KACPERSKI OR PRZEMYSŁAW SEMPRUCH!!!!!!!!!!
                return $element != 'MOT-TEST-LIST'
                // !!!!!!! DO NOT ADD TO THIS LIST, ASK WITOLD KACPERSKI OR PRZEMYSŁAW SEMPRUCH!!!!!!!!!!
                && $element != 'SLOTS-PURCHASE-INSTANT-SETTLEMENT'
                // !!!!!!! DO NOT ADD TO THIS LIST, ASK WITOLD KACPERSKI OR PRZEMYSŁAW SEMPRUCH!!!!!!!!!!
                && $element != 'SLOTS-TRANSACTION-READ-FULL'
                // !!!!!!! DO NOT ADD TO THIS LIST, ASK WITOLD KACPERSKI OR PRZEMYSŁAW SEMPRUCH!!!!!!!!!!
                && $element != 'CERTIFICATE-PRINT'; //Required by finance user
                // !!!!!!! DO NOT ADD TO THIS LIST, ASK WITOLD KACPERSKI OR PRZEMYSŁAW SEMPRUCH!!!!!!!!!!
            });
        $repeated ['system_site'] = ArrayUtils::filter($repeated['system_site'],
            function ($element)
            {
                // !!!!!!! DO NOT ADD TO THIS LIST, ASK WITOLD KACPERSKI OR PRZEMYSŁAW SEMPRUCH!!!!!!!!!!
                return $element != 'CERTIFICATE-PRINT';
                // !!!!!!! DO NOT ADD TO THIS LIST, ASK WITOLD KACPERSKI OR PRZEMYSŁAW SEMPRUCH!!!!!!!!!!
            });
        $permissionRepeats = ArrayUtils::anyMatch(
            $repeated, function (array $collection) {
            return !empty($collection);
        });

        if ($permissionRepeats) {
            throw new \Exception(
                'One permission cannot be used on different levels. The invalid permission(s):' .
                print_r($repeated, true)
            );
        }
    }
}
