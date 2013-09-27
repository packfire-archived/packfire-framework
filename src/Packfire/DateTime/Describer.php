<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\DateTime;

use Packfire\Text\Inflector;
use Packfire\Text\Text;
use Packfire\Collection\ArrayList;

/**
 * Describes the period of time span between two DateTime objects
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\DateTime
 * @since 2.0.0
 */
class Describer
{
    /**
     * The components of the time span
     * @var array
     * @since 2.0.0
     */
    private $components = array('day', 'hour', 'minute', 'second');

    /**
     * The adjectives to be used
     * @var string
     * @since 2.0.0
     */
    private $adjectives = array('day' => 'day', 'hour' => 'hour',
        'minute' => 'min', 'second' => 'sec', 'and' => 'and', 'comma' => ', ');

    /**
     * Flag whether adjectives should be quantified or not
     * @var boolean
     * @since 2.0.0
     */
    private $quantify = true;

    /**
     * Flag whether textual listing is performed on the output description
     * @var boolean
     * @since 2.0.0
     */
    private $listing = true;

    /**
     * The number of components to describe
     * @var integer
     * @since 2.0.0
     */
    private $limit;

    /**
     * Create a new Describer object
     * @since 2.0.0
     */
    public function __construct()
    {
    }

    /**
     * Get or set the number of components to describe
     * @param integer $limit (optional) If set, the number of component to
     *                  describe will be set to this value. If set to null,
     *                  all components will be returned.
     * @return integer Returns the number of components. If there is no limit,
     *                  null will be returned.
     * @since 2.0.0
     */
    public function limit($limit = null)
    {
        if (func_num_args()) {
            $this->limit = $limit;
        }

        return $this->limit;
    }

    /**
     * Get or set if the adjectives should be quantified or not
     * @param  boolean $quantify (optional) If set, the value will be set to this.
     * @return boolean Retusn true if adjectives should be quantified, false
     *      otherwise.
     * @since 2.0.0
     */
    public function quantify($quantify = null)
    {
        if (func_num_args()) {
            $this->quantify = $quantify;
        }

        return $this->quantify;
    }

    /**
     * Get or set if Describer should perform text listing on description
     * @param boolean $listing (optional) If set to true, listing will be done
     *              on the description, false otherwise.
     * @return boolean Returns whether listing is flagged.
     * @since 2.0.0
     */
    public function listing($listing = null)
    {
        if (func_num_args()) {
            $this->listing = $listing;
        }

        return $this->listing;
    }

    /**
     * Get or set the adjectives to describe the components
     * @param  array|ArrayList $adjectives (optional) If set, the adjectives will be set to this values
     * @return array           Returns the array of adjectives used to describe the components
     * @since 2.0.0
     */
    public function adjectives($adjectives = null)
    {
        if (func_num_args()) {
            if ($adjectives instanceof ArrayList) {
                $adjectives = $adjectives->toArray();
            }
            $this->adjectives = array_merge($this->adjectives, $adjectives);
        }

        return $this->adjectives;
    }

    /**
     * Describe the time period between two date/time
     *
     * Produces results such as : 1 hour, 24 mins and 20 secs
     *
     * @param DateTime $dateTime1 The first date time
     * @param DateTime $dateTime2 (optional) The second date time. If not set,
     *              the current date/time will be used.
     * @return string Returns the text description of the time period
     * @since 2.0.0
     */
    public function describe($dateTime1, $dateTime2 = null)
    {
        if (!$dateTime2) {
            $dateTime2 = DateTime::now();
        }
        $compare = $dateTime1->compareTo($dateTime2);
        /* @var $timeSpan Packfire\DateTime\TimeSpan */
        switch ($compare) {
            case 0:
                return 'now';
                break;
            case 1:
                $timeSpan = $dateTime2->subtract($dateTime1);
                break;
            case -1:
                $timeSpan = $dateTime1->subtract($dateTime2);
                break;
        }
        $desc = array();
        $count = 0;
        /* @var $timeSpan TimeSpan */
        foreach ($this->components as $component) {
            if (isset($this->adjectives[$component])) {
                $value = $timeSpan->$component();
                if ($value > 0) {
                    $desc[] = $value . ' ' . ($this->quantify
                            ? (Inflector::quantify($value, $this->adjectives[$component]))
                            : $this->adjectives[$component]);
                    ++$count;
                    if ($this->limit && $count >= $this->limit) {
                        break;
                    }
                }
            }
        }

        return $this->listing
                ? Text::listing($desc, $this->adjectives['and'], $this->adjectives['comma'])
                : implode(' ', $desc);
    }
}
