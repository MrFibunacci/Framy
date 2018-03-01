<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Stopwatch;


    /**
     * Represents an Period for an Event.
     *
     * @package app\framework\Component\Stopwatch
     */
    class StopwatchPeriod
    {
        private $start;
        private $end;
        private $memory;

        /**
         * Constructor.
         *
         * @param int $start The relative time of the start of the period (in milliseconds)
         * @param int $end   The relative time of the end of the period (in milliseconds)
         */
        public function __construct($start, $end)
        {
            $this->start = (int) $start;
            $this->end = (int) $end;
            $this->memory = memory_get_usage(true);
        }

        /**
         * Gets the time spent in this period.
         *
         * @return int The period duration (in milliseconds)
         */
        public function getDuration()
        {
            return $this->end - $this->start;
        }

        /**
         * Gets the relative time of the end of the period.
         *
         * @return int The time (in milliseconds)
         */
        public function getEndTime()
        {
            return $this->end;
        }

        /**
         * Gets the memory usage.
         *
         * @return int The memory usage (in bytes)
         */
        public function getMemory()
        {
            return $this->memory;
        }

        /**
         * Gets the relative time of the start of the period.
         *
         * @return int The time (in milliseconds)
         */
        public function getStartTime()
        {
            return $this->start;
        }
    }