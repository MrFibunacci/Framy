<?php
    return [
        /*
        |--------------------------------------------------------------------------
        | View Storage Paths
        |--------------------------------------------------------------------------
        |
        | Most templating systems load templates from disk. Here you may specify
        | an array of paths that should be checked for your views. Of course
        | the usual Framy view path has already been registered for you.
        |
        | You can add an disk from the filesystem or just an Absolute Path.
        | e.g: "disk:view", realpath(dirname(__FILE__).'/who/reads/this/smells')
        |
        */

        'paths' => [
            "disk:view",
        ],

        /*
        |--------------------------------------------------------------------------
        | Compiled View Path
        |--------------------------------------------------------------------------
        |
        | This option determines where all the compiled Blade templates will be
        | stored for your application. Typically, this is within the storage
        | directory. However, as usual, you are free to change this value.
        |
        | Basically the same as above but with only one dir/disk.
        |
        */

        'compiled' => "disk:viewCompiled",
    ];