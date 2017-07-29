<?php
    return [
        /*-------------------------------------------------------------------------
        | Default Filesystem Disk
        |--------------------------------------------------------------------------
        |
        | Here you may specify the default filesystem disk that should be used
        | by the framework. A "local" driver, as well as a variety of cloud
        | based drivers are available for your choosing. Just store away!
        |
        | Supported: "local"       Not yet -> , "ftp", "s3", "rackspace"
        |
        */

        'default' => 'local',

        /*
        |--------------------------------------------------------------------------
        | Filesystem Disks
        |--------------------------------------------------------------------------
        |
        | Here you may configure as many filesystem "disks" as you wish, and you
        | may even configure multiple disks of the same driver. Defaults have
        | been setup for each driver as an example of the required options.
        |
        */

        'disks' => [
            'public' => [
                'driver' => 'local',
                'root' => realpath(dirname(__FILE__)."/../public"),
                'visibility' => 'public',
            ],

            'view' => [
                'driver' => 'local',
                'root' => realpath(dirname(__FILE__).'/../storage/templates/')
            ],

            'viewCompiled' => [
                'driver' => 'local',
                'root' => realpath(dirname(__FILE__).'/../storage/templates/templates_c')
            ],

            'language' => [
                'driver' => 'local',
                'root' => realpath(dirname(__FILE__).'/../storage/lang/')
            ]
        ]
    ];