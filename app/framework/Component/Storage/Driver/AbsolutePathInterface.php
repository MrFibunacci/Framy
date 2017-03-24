<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 */

    namespace app\framework\Component\Storage\Driver;


    /**
     * Interface AbsolutePathInterface
     *
     * @package app\framework\Component\Storage\Driver
     */
    interface AbsolutePathInterface
    {
        /**
         * @param $key
         *
         * @return mixed
         */
        public function getAbsolutePath($key);
    }