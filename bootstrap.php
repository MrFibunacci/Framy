<?php
    define('FRAMY_START', microtime(true));
    define('ROOT_PATH', realpath(__DIR__));

    /*---------------------------------------
    | Register Class Loader
    |----------------------------------------
    |
    | The house made Class auto loader.
    |
    */
    require("app/framework/Component/ClassLoader/ClassLoader.php");

    $autoLoader = new \app\framework\Component\ClassLoader\ClassLoader();
    $autoLoader->register();

    if(! function_exists("dd")){
        /**
         * Little helper called dump and die
         * @param $val
         */
        function dd($val)  {
            var_dump($val);die;
        }
    }

    if(! function_exists("view")){
        /**
         * Get the evaluated view contents for the given view.
         *
         * @param  string  $view        Name of template file.
         * @param  array   $data        Data to set values in template file
         * @param  array   $mergeData   Some shit I don't know yet
         * @return \app\framework\Component\View\View
         */
        function view($view = null, $data = [], $mergeData = []){
            $Factory = new \app\framework\Component\View\Factory($view);
            dd($Factory);
            $View = new \app\framework\Component\View\View($view, $data, $Factory->getNames(), $Factory->engine);
            $View->render();
        }
    }
