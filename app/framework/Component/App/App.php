<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\App;

    class App
    {
        private $defaultNamespaces = [
            '\app\custom\database\migrations',
            '\app\custom\database\seeders',
            '\app\custom\Http\Controller',
            '\app\custom\Http\Middleware',
            '\app\custom\Models',
        ];

        /**
         * @var array
         */
        private $Instances = [];

        public function call($classMethod, $param = [])
        {
            $method = null;

            $this->registerNewClass($classMethod, $method);

            return $this->callMethod($classMethod, $method, $param);
        }

        public function getClassInstance($className) 
        {
            $method = null;
            $this->registerNewClass($className, $method);

            return $this->Instances[$className];
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

        private function registerNewClass(&$class, &$method)
        {
            $method = explode("@", $class)[1];
            $class = self::validateClass(explode("@", $class)[0]);

            if(! self::checkIfClassIsRegistered($class)){
                $this->Instances[$class] = new $class;
            }
        }

        private function callMethod($class, $method, $param)
        {
            return call_user_func_array(array($this->Instances[$class], $method), $param);
        }

        private function validateClass($class)
        {
            foreach($this->defaultNamespaces as $namespace){
                //var_dump($namespace . "\\" . $class);
                if(class_exists($namespace . "\\" . $class)){
                    return $namespace . "\\" . $class;
                } elseif (class_exists($class)) {
                    return $class;
                }
            }

            throw new \Exception("Class not found. Maybe changing '/' to '\' helps.");
        }
    }
