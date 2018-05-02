<?php
    /*-------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | This file is where you may define all of the routes that are handled
    | by your application. Just tell Framy the URIs it should respond
    | to using a Closure or controller method. Build something great!
    |
    */

    use \app\framework\Component\Route\Klein\Klein;

    $klein = new Klein();

    $klein->respond("GET", "/", function(){
        $Storage = new \app\framework\Component\Storage\Storage("view");
        $Dir = new \app\framework\Component\Storage\Directory\Directory("", $Storage);
        foreach ($Dir->filter("*.tpl") as $file){
            var_dump($file);
        }
    });

    // add more routes here ...

    $klein->dispatch();
