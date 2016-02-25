<?php

namespace DvsaCommon\Model;

/**
 * @deprecated This data should be accessed via catalog
 */
class VtsStatus
{
    public static function getStatuses()
    {
        return [
            'AV' => 'Approved',
            'AP' => 'Applied',
            'RE' => 'Retracted',
            'RJ' => 'Rejected',
            'LA' => 'Lapsed',
            'EX' => 'Extinct',
        ];
    }
}
