<?php

namespace DvsaCommon\Utility;

/**
 * Utility functions operating on current request header.
 * Zend 2 provided functions are failing in an arbitrarily manner
 * so that we needed to implement our bespoke solution based on native functions.
 * The symptoms were that for UATDataSetup.java process $request->getHeaders()->get($headerName)
 * used to return null sometimes, even the header was there. It seemed as timing issue because putting sleep before
 * this instruction made it more stable.
 * The approach below is proven to work in any circumstances.
 */
final class HeaderUtils
{
    private static function getCurrentRequestHeaders()
    {
        if (function_exists("getallheaders")) {
            return getallheaders();
        } else {
            // getallheaders is defined for Apache only... other servers like nginx expose content type as HTTP vars
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))]
                        = $value;
                }
            }
            return $headers;
        }
    }

    /**
     * Retrieves content-type header from the current request, or if undefined, returns null
     * @return string|null
     */
    public static function getContentType()
    {
        $headers = self::getCurrentRequestHeaders();
        foreach ($headers as $key => $value) {
            if (strtolower($key) === "content-type") {
                if (strstr($value, ';')) {
                    $contentTypeParts = explode(';', $value);
                    return $contentTypeParts[0];
                }
                return $value;
            }
        }
        return null;
    }
}
