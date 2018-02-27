<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Route;

    use app\framework\Component\Route\Klein\Klein;

    class Route
    {
        private $KleinInstance;

        /**
         * Route constructor.
         */
        public function __construct()
        {
            $this->KleinInstance = new Klein();
        }

        public function get($path, $callback)
        {
            //dd(getStringBetween($path, "[", "]"));
            $this->respond("get", $path, function($request) use ($callback) {
                app($callback, [$request->ID]);
            });
        }
/*
        public function post($path, $callback)
        {
            $this->respond("post", $path, function($request) use ($callback) {
                app($callback);
            });
        }

        public function put($path, $callback)
        {
            $this->respond("put", $path, function($request) use ($callback) {
                app($callback);
            });
        }

        public function delete($path, $callback)
        {
            $this->respond("delete", $path, function($request) use ($callback) {
                app($callback);
            });
        }

        public function patch($path, $callback)
        {
            $this->respond("post", $path, function($request) use ($callback) {
                app($callback);
            });
        }
*/
        private function respond($method, $path, $callback)
        {
            $this->KleinInstance->respond($method, $path, $callback);
        }

        public function dispatch()
        {
            $this->KleinInstance->dispatch();
        }
    }