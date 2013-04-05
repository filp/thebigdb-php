<?php
/**
 * This file is part of filp/thebigdb-php, a package
 * for communicating with the thebigdb.com API.
 *
 * @author  Filipe Dobreira <https://github.com/filp>
 * @license MIT
 */
namespace TheBigDB\Api;
use DateTime;
use BadMethodCallException;
use RuntimeException;

/**
 * TheBigDB\Api\Search
 * Represents a (GET) search request
 */
class Search
{
    // YYYY-MM-DD HH:MM:SS
    const PERIOD_DATE_FORMAT = 'Y-m-d H:i:s';
    const PERIOD_DATE_ZERO   = '0000-00-00 00:00:00';

    protected $periodFrom;
    protected $periodTo;
    protected $page;
    protected $minNodes;
    protected $maxNodes;
    protected $exactNodes;
    protected $nodes = array();
    protected $extra = array();

    /**
     * Builds a hash of arguments to be used for the API request.
     * @return array
     */
    public function toRequestHash()
    {
        // Bail out early if we have nothing to search for.
        if(count($this->nodes) == 0) {
            throw new RuntimeException(
                'Cannot perform a query without any specified nodes; use ' . __CLASS__ . '::find'
            );
        }

        $args = array();

        if($period = $this->getPeriodHash()) {
            $args['period'] = $period;
        }

        if($this->page !== null) {
            $args['page'] = $page;
        }

        // No point checking for min/max if we have an exact value
        if($this->exactNodes !== null) {
            $args['nodes_count_exactly'] = $this->exactNodes;
        } else {
            if($this->minNodes !== null) {
                $args['nodes_count_min'] = $this->minNodes;
            }
            if($this->maxNodes !== null) {
                $args['nodes_count_max'] = $this->maxNodes;
            }
        }

        $period['nodes'] = $this->nodes;

        // Merge any additional arguments provided through
        // Search::with
        $args = array_merge($args, $extra);

        return $args;
    }

    /**
     * @api
     * @params array|string $node,...
     * @return self
     */
    public function find()
    {
        if(func_num_args() == 0) {
            throw new BadMethodCallException(
                __METHOD__ . ' expects at least one node argument'
            );
        }

        $this->nodes = $this->nodes + $nodes;
        return $this;
    }

    /**
     * @api
     * @param  int $nodesCount
     * @return self
     */
    public function minimumNodes($nodesCount)
    {
        $this->minNodes = (int) $nodesCount;
        return $this;
    }

    /**
     * @api
     * @param  int $nodesCount
     * @return self
     */
    public function maximumNodes($nodesCount)
    {
        $this->maxNodes = (int) $nodesCount;
        return $this;
    }

    /**
     * @api
     * @param  int $nodesCount
     * @return self
     */
    public function totalNodes($nodesCount)
    {
        $this->exactNodes = (int) $nodesCount;
        $this->minNodes   = null;
        $this->maxNodes   = null;
        return $this;
    }

    /**
     * @api
     * @param  array $extra
     * @return self
     */
    public function with(array $extra)
    {
        $this->extra = $extra;
        return $this;
    }

    /**
     * @api
     * @param  int $page
     * @return self
     */
    public function page($page)
    {
        $this->page = (int) $page;
        return $this;
    }

    /**
     * @api
     * @param  DateTime $from
     * @param  DateTime $to
     * @return self
     */
    public function period(DateTime $from, DateTime $to)
    {
        return $this->from($from)->to($to);
    }

    /**
     * @api
     * @param  DateTime $date
     * @return self
     */
    public function from(DateTime $date)
    {
        $this->periodFrom = $date;
        return $this;
    }

    /**
     * @api
     * @param  DateTime $date
     * @return self
     */
    public function to(DateTime $date)
    {
        $this->periodTo = $date;
        return $this;
    }

    /**
     * Returns a properly-formed period hash, or false
     * if the user did not specify any period data.
     * @return array
     */
    protected function getPeriodHash()
    {
        if($this->periodTo === null && $this->periodFrom === null) {
            return false;
        }

        $period = array(
            'from' => static::PERIOD_DATE_ZERO,
            'to'   => ''
        );

        if($this->periodFrom) {
            $period['from'] = $this->periodFrom->format(static::PERIOD_DATE_FORMAT);
        }

        // If no 'to' date is set, assume the current date.
        if($this->periodTo) {
            $period['to'] = $this->periodTo->format(static::PERIOD_DATE_FORMAT);
        } else {
            $period['to'] = date(static::PERIOD_DATE_FORMAT);
        }

        return $period;
    }
}
