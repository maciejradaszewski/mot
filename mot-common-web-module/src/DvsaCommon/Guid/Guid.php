<?php

namespace DvsaCommon\Guid;

use Zend\Math\Rand;

/**
 * Class Guid
 *
 * @package DvsaCommon\Guid
 */
class Guid
{
    public static function newGuid()
    {
        $charList = array_merge(range('A', 'F'), range(0, 9));
        $source = Rand::getString(32, implode($charList), true);
        $uuid = substr($source, 0, 8) . '-' .
                substr($source, 8, 4) . '-' .
                substr($source, 12, 4) . '-' .
                substr($source, 16, 4) . '-' .
                substr($source, 20, 12);

        return $uuid;
    }
}
