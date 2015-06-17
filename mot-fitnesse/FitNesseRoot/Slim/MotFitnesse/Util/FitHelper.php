<?php

namespace MotFitnesse\Util;

class FitHelper
{
    public static function decode($value, $map)
    {
        foreach ($map as $src => $dst) {
            if ($value === $src) {
                return $dst;
            }
        }
        throw new \InvalidArgumentException("Decoding failed. Missing source value for: $value");
    }
}
 