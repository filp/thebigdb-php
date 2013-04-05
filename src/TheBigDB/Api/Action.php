<?php
/**
 * This file is part of filp/thebigdb-php, a package
 * for communicating with the thebigdb.com API.
 *
 * @author  Filipe Dobreira <https://github.com/filp>
 * @license MIT
 */
namespace TheBigDB\Api;

/**
 * TheBigDB\Api\Action
 */
abstract class Action
{
    protected $api;

    /**
     * @param TheBigDB\Api $api
     */
    public function setApi(TheBigDB\Api $api)
    {
        $this->api = $api;
    }

    /**
     * @return TheBigDB\Api
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     */
    public function result()
    {
        return $this->getApi()->execute($this);
    }

    /**
     * Returns the HTTP verb for this action
     * @return string
     */
    abstract public function getMethod();

    /**
     * Returns the namespace for this action
     * @example "statements/search"
     * @return  string
     */
    abstract public function getNamespace();

    /**
     * Builds a hash of arguments to be used for the API request.
     * @return array
     */
    abstract public function toRequestHash();
}
