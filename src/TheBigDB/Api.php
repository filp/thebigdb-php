<?php
/**
 * This file is part of filp/thebigdb-php, a package
 * for communicating with the thebigdb.com API.
 *
 * @author  Filipe Dobreira <https://github.com/filp>
 * @license MIT
 */
namespace TheBigDB;
use TheBigDB\Api\Search;

/**
 * TheBigDB\Api
 */
class Api
{
    private $errorsAsExceptions = false;

    /**
     * Should errors in an API response be translated to
     * exceptions?
     *
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
     * @api
     */
    public function search()
    {
        return new Search($this);
    }
}
