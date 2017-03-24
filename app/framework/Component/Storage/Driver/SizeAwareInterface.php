<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 */

    namespace app\framework\Component\Storage\Driver;

    /**
     * Interface SizeAwareInterface
     *
     * @package app\framework\Component\Storage\Driver
     */
    interface SizeAwareInterface {

        /**
         * Get file size
         *
         * @param $key
         *
         * @return int
         */
        public function getSize($key);
    }