<?php

namespace App\Services;

use Illuminate\Contracts\Hashing\Hasher;

class Cake255Hash implements Hasher
{
    /**
     * @var null
     */
    protected $salt = null;

    /**
     * Cake255Hash constructor.
     *
     * @param null $salt
     */
    public function __construct($salt = null)
    {
        $this->salt = $salt;
    }

    /**
     * Hash the given value.
     *
     * @param  string $value
     * @param  array  $options
     * @return string
     */
    public function make($value, array $options = [])
    {
        if (function_exists('mhash')) {
            return bin2hex(mhash(MHASH_SHA256, $this->salt.$value));
        }

        return $this->hash('sha256', $this->salt.$value);
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param  string $value
     * @param  string $hashedValue
     * @param  array  $options
     * @return boolean
     */
    public function check($value, $hashedValue, array $options = [])
    {
        return $this->make($value, $options) === $hashedValue;
    }

    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param  string $hashedValue
     * @param  array  $options
     * @return boolean
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }
}
