<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\ClassLoader\Loader;

    //require __DIR__ . "/AbstractLoader.php";

    /**
     * Psr4 autoloader
     *
     * @package app\framework\Component\ClassLoader\Loader
     */
    class Psr4 //extends AbstractLoader
    {
        /**
         * Returns File path or false
         *
         * @param $name
         *
         * @return mixed
         */
        public function findClass($name)
        {
            // TODO: Implement findClass() method.
            return false;
        }

    }