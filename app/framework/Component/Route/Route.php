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
            $this->respond("get", $path, function($request) use ($callback) {
                app($callback);
            });
        }

        public function respond($method, $path, $callback)
        {
            $this->KleinInstance->respond($method, $path, $callback);

            $this->KleinInstance->dispatch();
        }
    }