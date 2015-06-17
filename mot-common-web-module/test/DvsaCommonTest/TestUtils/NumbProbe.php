<?php

namespace DvsaCommonTest\TestUtils;

/**
 *
 */
class NumbProbe
{
    public function __call($methodName, $callArgs)
    {
        return $this;
    }
}
