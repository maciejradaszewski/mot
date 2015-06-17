<?php

/**
 * An expirable cache for header tokens for HTTP requests. Keyed by strings of the form 'username:password'.
 */
class TokenCache
{

    const TOKEN_EXPIRY_TIME = 2400; // sec

    private static $tokenCache = [];

    public static function clearAuthCache()
    {
        self::$tokenCache = [];
    }

    public static function getToken($key)
    {
        if (array_key_exists($key, self::$tokenCache)) {
            $tokenEntry = self::$tokenCache[$key];
            $setUpTimestamp = $tokenEntry['timestamp'];
            if ($setUpTimestamp + self::TOKEN_EXPIRY_TIME <= time()) {
                return $tokenEntry['token'];
            }
            unset(self::$tokenCache[$key]);
        }
        return null;
    }

    public static function addToken($key, $token)
    {
        self::$tokenCache[$key] = ['timestamp' => time(), 'token' => $token];
    }
}
