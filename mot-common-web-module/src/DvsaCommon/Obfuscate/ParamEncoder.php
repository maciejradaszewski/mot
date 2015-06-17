<?php

namespace DvsaCommon\Obfuscate;

use Zend\Crypt\BlockCipher;

class ParamEncoder
{
    /**
     * @param $id
     *
     * @return string
     */
    public function encode($param)
    {
        $base64 = base64_encode($param);

        return rtrim(strtr($base64, '+/', '-_'), '=');
    }

    /**
     * base64_decode returns false if the string is not base64
     *
     * @param $id
     *
     * @return string
     */
    public function decode($encodedParam)
    {
        $param = strtr($encodedParam, '-_', '+/');

        $mod4 = strlen($param) % 4;
        if ($mod4) {
            $param .= substr('====', $mod4);
        }

        return base64_decode($param);
    }
}
