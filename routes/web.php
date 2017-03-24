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
/*    $klein = new app\framework\Component\Route\Klein\Klein();

    $klein->respond('GET', 'user', function () {
        return 'Welcome :-)';
    });

    // add more routes here ...

    $klein->dispatch();*/
    use app\framework\Component\Route\Config;
    use app\framework\Component\Route\Route;

    //config
    Config::set('basepath', 'My%20PHP%20Framework/public');

    //init routing
    Route::init();

    Route::add('user/(.*)/edit',function($id){
        echo 'Edit user with id '.$id;
    });

    Route::add('',function(){
        //echo 'Welcome :-)';
    });

    // add more here

    Route::run();