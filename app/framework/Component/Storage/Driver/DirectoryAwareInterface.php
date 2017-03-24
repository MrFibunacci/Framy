<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 */

    namespace app\framework\Component\Storage\Driver;

    /**
     * Interface DirectoryAwareInterface
     *
     * @package app\framework\Component\Storage\Driver
     */
    interface DirectoryAwareInterface
    {
        /**
         * Check if key is directory
         *
         * @param string $key
         *
         * @return boolean
         */
        public function isDirectory($key);
    }