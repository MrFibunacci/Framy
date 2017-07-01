<?php
    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | This file is where you may define all of the routes that are handled
    | by your application. Just tell Framy the URIs it should respond
    | to using a Closure or controller method. Build something great!
    |
    */

    $klein = new app\framework\Component\Route\Klein\Klein();

    $klein->respond('GET', 'user', function () {
        return 'Welcome :-)';
    });

    // add more routes here ...

    $klein->dispatch();
