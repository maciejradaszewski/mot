<?php
namespace DvsaMotApi\Service\Helper;

/**
 * Class ExtractionHelper
 *
 * @package DvsaMotApi\Service\Helper
 */
class ExtractionHelper
{
    public static function unsetAuditColumns(&$data)
    {
        if (!$data) {
            return;
        }
        unset($data['createdOn']);
        unset($data['lastUpdatedOn']);
        unset($data['version']);
        unset($data['lastUpdatedBy']);
        unset($data['createdBy']);
    }
}
