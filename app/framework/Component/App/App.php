<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\App;

    class App
    {
        private $defaultNamespaces = [
            '\app\custom\database\migrations',
            '\app\custom\database\seeders',
            '\app\custom\http\Controller',
            '\app\custom\http\Middleware',
            '\app\custom\Models',
        ];

        /**
         * @var array
         */
        private $Instances = [];

        public function call($classMethod, $param = [])
        {
            $class = self::validateClass(explode("@", $classMethod)[0]);
            $method = explode("@", $classMethod)[1];

            if(! self::checkIfClassIsRegistered($class)){
                self::registerNew($class);
            }
            //2. get instance and call method
            return $this->callMethod($class, $method, $param);
        }

        private function checkIfClassIsRegistered($class)
        {
            foreach($this->Instances as $Class){
                if(get_class($Class) == $class){
                    return true;
                }
            }
            return false;
        }

        private function registerNew($class)
        {
            $this->Instances[$class] = new $class;
        }

        private function callMethod($class, $method, $param)
        {
            return call_user_func_array(array($this->Instances[$class], $method), $param);
        }

        private function validateClass($class)
        {
            foreach($this->defaultNamespaces as $namespace){
                if(class_exists($namespace . "\\" . $class)){
                    return $namespace . "\\" . $class;
                } elseif (class_exists($class)) {
                    return $class;
                }
            }

            throw new \Exception("Class not found. Maybe changing '/' to '\' helps.");
        }
    }
