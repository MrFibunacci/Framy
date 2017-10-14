<?php
    /*---------------------------------------
    | index.php
    |----------------------------------------
    |
    | This is the entry point of the hole website!
    | This is were the Magic begins.
    |
    */


    /*-------------------------------------------------------------------------
    | Turn On The Lights
    |--------------------------------------------------------------------------
    |
    | We need to illuminate PHP development, so let us turn on the lights.
    | This bootstraps the framework and gets it ready for use, then it
    | will load up this application so that we can run it and send
    | the responses back to the browser and delight our users.
    |
    */

    include(realpath(__DIR__."/../bootstrap/bootstrap.php"));

    /*---------------------------------------
    | Register Routes
    |----------------------------------------
    |
    | Here you are registering web and Components routes
    |
    */

    include(realpath(__DIR__."/../routes/web.php"));
    include(realpath(__DIR__."/../routes/api.php"));