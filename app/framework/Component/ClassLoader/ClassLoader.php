<?php
/**
 * Created by IntelliJ IDEA.
 * User: MrFibunacci
 * Date: 16.01.2017
 * Time: 01:29
 */

    namespace app\framework\Component\ClassLoader;

    use app\framework\Component\ClassLoader\Loader;

    require_once __DIR__ . "/Loader/Psr0.php";
    require_once __DIR__ . "/Loader/Psr4.php";

    class ClassLoader
    {
        public function register()
        {
            spl_autoload_register(array($this, 'getClass'));
        }

        public function getClass($class)
        {
            if($file = $this->loadClass($class)){
                require($file);

                return true;
            }
            return false;
        }

        public function loadClass($class)
        {
            $file = false; //Loader\Psr4::getInstance()->findClass($class);
            if (!$file) {
                $file = Loader\Psr0::getInstance()->findClass($class);
            }
            return $file;
        }
    }