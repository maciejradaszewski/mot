<?php

namespace DvsaCommon\Dto;

/**
 * Class AbstractDataTransferObject
 *
 * So much quality thanks to this comment.
 */
abstract class AbstractDataTransferObject
{
    /**
     * The underscore is here to avoid conflicts with getter methods of inheriting DTO objects.
     * Do not remove, unit test will be fail.
     *
     * @return string Class path
     */
    public function get_class()
    {
        return get_class($this);
    }
}
