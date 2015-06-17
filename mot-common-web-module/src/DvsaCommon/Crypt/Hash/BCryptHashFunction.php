<?php

namespace DvsaCommon\Crypt\Hash;

/**
 * Implements Bcrypt-based hashing function.
 * Minimum available cost = 4
 * Minimum salt length = 22
 * Maximum secret length = 72
 */
class BCryptHashFunction implements HashFunctionInterface
{
    const MAX_SECRET_LENGTH = 72;
    const MIN_COST = 4;
    private $cost = 10;


    /**
     * Hashes secret with Bcrypt algorithm. Possible to use salt of minimum lenth of 22
     *
     * @inheritdoc
     */
    public function hash($secret, $salt = null)
    {
        if (strlen($secret) > self::MAX_SECRET_LENGTH) {
            throw new \InvalidArgumentException('Secret cannot be longer than 72 characters!');
        }

        $options = ['cost' => $this->cost];
        if ($salt) {
            $options += ['salt' => $salt];
        }
        $result = password_hash($secret, PASSWORD_BCRYPT, $options);
        if ($result === false) {
            throw new \RuntimeException(__CLASS__ . '::' . __METHOD__ . ' failed');
        }
        return $result;
    }

    /**
     * Verifies if secret is equal to hashed secret
     * Salt is unimportant as encoded into hashed.
     *
     * @param string      $secret
     * @param string      $hash
     * @param null|string $salt
     *
     * @return bool
     */
    public function verify($secret, $hash, $salt = null)
    {
        return password_verify($secret, $hash);
    }

    /**
     * Changes default cost of algorithm. Minimum value 4.
     *
     * @param $cost
     *
     * @return $this
     */
    public function setCost($cost)
    {
        if ($cost < self::MIN_COST) {
            throw new \InvalidArgumentException('Minimum cost: ' . self::MIN_COST . ' exceeded');
        }
        $this->cost = $cost;
        return $this;
    }
}
