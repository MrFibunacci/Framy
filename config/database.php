<?php

    return [

        /*
         |----------------------------------------
         | Database Connections
         |----------------------------------------
         |
         | Here you can configure your connections. You can ether use the
         | here shown syntax or the syntax from Medoo. The key of the array
         | in the example 'mysql' is the name of the connection.
         |
         */

        'connections' => [
            'mysql' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'port' => 3306,
                'database' => 'YOUR_DATABASE',
                'username' => 'YOUR_USERNAME',
                'password' => 'YOUR_PASSWORD',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ],
        ]
    ];