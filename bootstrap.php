<?php
    define('FRAMY_START', microtime(true));
    define('ROOT_PATH', realpath(__DIR__)."\\");

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

    /**
     * Little helper called dump and die
     * @param $val
     */
    function dd($val){
        var_dump($val);die;
    }