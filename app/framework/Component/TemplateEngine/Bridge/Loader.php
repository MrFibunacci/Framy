<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\TemplateEngine\Bridge;

    use Smarty;

    require("Smarty/libs/Smarty.class.php");
    require('mustache/src/Mustache/Autoloader.php');


    class Loader
    {
        public static function getBridge($useBridge)
        {
            switch($useBridge){
                case "Smarty":
                    return new Smarty();
                    break;
                case "Mustache":
                    \Mustache_Autoloader::register();
                    return new \Mustache_Engine();
                    break;
            }
        }
    }