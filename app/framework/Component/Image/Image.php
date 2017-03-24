<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Image;

    class Image {

        /**
         * @var array Default configuration params.
         */
        private static $defaultSettings = [
            "Library" => "gd",
            "Quality" => 90
        ];

        /**
         * @return array
         */
        public static function getDefaultSettings()
        {
            return self::$defaultSettings;
        }


    }