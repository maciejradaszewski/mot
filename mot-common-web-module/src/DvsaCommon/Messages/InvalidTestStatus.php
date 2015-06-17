<?php
namespace DvsaCommon\Messages;

use DvsaCommon\Enum\MotTestStatusName;

// Used both in api and frontend
/**
 * Maps test status to error message.
 */
class InvalidTestStatus
{
    public static function getMessage($status)
    {
        if ($status === MotTestStatusName::ABORTED_VE) {
            return "This test has been aborted by DVSA and cannot be continued";
        } elseif ($status !== MotTestStatusName::ACTIVE) {
            return "This test is completed and cannot be changed";
        } else {
            return '';
        }
    }
}
