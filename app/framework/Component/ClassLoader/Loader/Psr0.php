<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\ClassLoader\Loader;

    require __DIR__ . "/AbstractLoader.php";

    /**
     * Psr0 autoloader
     *
     * @package app\framework\Component\ClassLoader\Loader
     */
    class Psr0 extends AbstractLoader
    {
        public function findClass($class)
        {
            $className = ltrim($class, '\\');
            $fileName  = '';
            $namespace = '';
            if ($lastNsPos = strrpos($className, '\\')) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

            if(file_exists(ROOT_PATH.DIRECTORY_SEPARATOR.$fileName))
                return ROOT_PATH.DIRECTORY_SEPARATOR.$fileName;

            return false;
        }
    }
