<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Storage\Driver;

    /**
     * Interface TouchableInterface
     *
     * @package app\framework\Component\Storage\Driver
     */
    interface TouchableInterface
    {
        /**
         * Touch a file (change time modified)
         *
         * @param $key
         *
         * @return mixed
         */
        public function touchKey($key);
    }