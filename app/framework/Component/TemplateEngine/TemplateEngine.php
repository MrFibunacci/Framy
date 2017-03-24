<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\TemplateEngine;


    use app\framework\Component\TemplateEngine\Bridge\Loader;

    class TemplateEngine
    {
        private $bridge;

        function __construct($useBridge)
        {
            $this->bridge = Loader::getBridge($useBridge);
        }

        /**
         * @return mixed
         */
        public function getBridge()
        {
            return $this->bridge;
        }
    }