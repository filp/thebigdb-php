<?php
/**
 * This file is part of filp/thebigdb-php, a package
 * for communicating with the thebigdb.com API.
 *
 * @author  Filipe Dobreira <https://github.com/filp>
 * @license MIT
 */
namespace TheBigDB;
use TheBigDB\Api\Action;
use TheBigDB\Api\Search;

/**
 * TheBigDB\Api
 */
class Api
{
    protected $errorsAsExceptions = false;
    protected $http;

    /** @todo setters */
    protected $endpoint = 'api.thebigdb.com/v1';

    /**
     * Should errors in an API response be translated to
     * exceptions?
     * @api
     * @param  bool $errorsAsExceptions
     * @return self
     */
    public function errorsAsExceptions($errorsAsExceptions)
    {
        $this->errorsAsExceptions = (bool) $errorsAsExceptions;
        return $this;
    }

    /**
     * Executes an action on the API
     * @todo  Abstract HTTP interface
     * @param TheBigDB\Api\Action $action
     */
    public function execute(Action $action)
    {
        $args      = $action->toRequestHash();
        $method    = $action->getMethod();
        $namespace = $action->getNamespace();
    }

    /**
     * Prepares a generic action instance
     * @param  TheBigDB\Api\Action $action
     * @return TheBigDB\Api\Action
     */
    protected function action(Action $action)
    {
        $action->setApi($this);
        return $action;
    }

    /**
     * @api
     */
    public function search()
    {
        return $this->action(new Search);
    }
}
