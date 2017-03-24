<?php
/**
 * Created by IntelliJ IDEA.
 * User: MrFibunacci
 * Date: 20.12.2016
 * Time: 01:42
 */

    namespace app\framework\Component\Storage\Disk;


    interface DiskInterface {
        /**
         *
         *
         * @param $diskName
         */
        function __construct($diskName);

        /**
         * Returns true of false if disk Exists
         *
         * @param $diskName
         * @return bool
         */
        public function isDisk($diskName);

        /**
         * Returns Path or false if path is not defined in config file.
         *
         * @return String|bool
         */
        public function getPath();
    }