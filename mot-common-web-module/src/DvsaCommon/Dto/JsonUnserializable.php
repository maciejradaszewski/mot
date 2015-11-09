<?php
/**
 * This file is part of the DVSA MOT Common project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommon\Dto;

/**
 * Objects implementing JsonSerializable can provide a custom hydration procedure populating internal properties from
 * a JSON array-representation.
 */
interface JsonUnserializable
{
    /**
     * Provide a custom hydration procedure populating internal properties from a JSON array-representation.
     *
     * @param array $data
     *
     * @since 1.21.0
     */
    public function jsonUnserialize(array $data);
}
