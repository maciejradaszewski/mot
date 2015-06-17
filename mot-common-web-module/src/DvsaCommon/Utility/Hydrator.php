<?php

namespace DvsaCommon\Utility;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Used to convert Doctrine Entities into arrays and back.
 *
 * Class Hydrator
 *
 * @package DvsaCommon\Utility
 */
class Hydrator extends ClassMethods
{


    public function __construct()
    {
        parent::__construct(false);
    }

    public function extract($object, array $propertiesToExtract = null)
    {
        $extractedData = parent::extract($object);

        if ($propertiesToExtract) {
            $extractedData = ArrayUtils::valuesByKeys($extractedData, $propertiesToExtract);
        }

        return $extractedData;
    }
}
