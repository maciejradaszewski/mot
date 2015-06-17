<?php

namespace DvsaCommon\Crypt\Hash;

/**
 * Exposes universal interface for hashing function
 */
interface HashFunctionInterface
{
    /**
     * Hashes given secret with predefined algorithm and optional salt
     *
     * @param   string    $secret
     *
     * @return string
     */
    public function hash($secret);

    /**
     * Verifies if secret is equal to hashed secret
     *
     * @param string      $secret
     * @param string      $hash
     *
     * @return bool
     */
    public function verify($secret, $hash);
}
