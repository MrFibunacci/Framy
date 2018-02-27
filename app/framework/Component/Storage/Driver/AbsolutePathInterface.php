<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
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