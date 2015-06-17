<?php

namespace DvsaCommon\Utility;

/**
 * Class RomanNumeralsConverter
 *
 * @package DvsaCommon\Utility
 */
class RomanNumeralsConverter
{
    public static function toRomanNumerals($num)
    {
        $n = intval($num);
        $res = '';

        /*** roman_numerals array  ***/
        $roman_numerals = array(
            'M'  => 1000,
            'CM' => 900,
            'D'  => 500,
            'CD' => 400,
            'C'  => 100,
            'XC' => 90,
            'L'  => 50,
            'XL' => 40,
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1);

        foreach ($roman_numerals as $roman => $number) {
            $matches = intval($n / $number);

            $res .= str_repeat($roman, $matches);

            $n = $n % $number;
        }

        return $res;
    }
}
