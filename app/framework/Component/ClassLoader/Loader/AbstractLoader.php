<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\ClassLoader\Loader;


    use app\framework\Component\StdLib\SingletonTrait;

    require_once realpath(__DIR__."/../../StdLib/SingletonTrait.php");

    abstract class AbstractLoader
    {
        use SingletonTrait;

        /**
         * Returns File path or false
         *
         * @param $name
         *
         * @return mixed
         */
        abstract public function findClass($name);
    }