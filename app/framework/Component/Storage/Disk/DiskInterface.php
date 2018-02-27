<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
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