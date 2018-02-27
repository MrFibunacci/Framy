<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\TemplateEngine;

    require("Smarty/Smarty.class.php");

    class TemplateEngine
    {
        private static $instance;

        function __construct()
        {
            $this::$instance = new \Smarty();
        }

        /**
         * @return \Smarty
         */
        public function getInstance()
        {
            return $this::$instance;
        }
    }