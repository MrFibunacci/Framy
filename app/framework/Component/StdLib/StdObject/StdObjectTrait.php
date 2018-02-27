<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\StdLib\StdObject;

    use app\framework\Component\StdLib\StdObject\ArrayObject\ArrayObject;
    use app\framework\Component\StdLib\StdObject\DateTimeObject\DateTimeObject;
    use app\framework\Component\StdLib\StdObject\StringObject\StringObject;
    use app\framework\Component\StdLib\StdObject\UrlObject\UrlObject;

    trait StdObjectTrait {
        /**
         * Creates an instance of Array Standard Object.
         *
         * @param array $array
         *
         * @return ArrayObject
         */
        protected static function arr($array = null)
        {
            return new ArrayObject($array);
        }

        /**
         * Create an instance of DateTime Standard Object.
         *
         * @param string|int  $time                     A date/time string. List of available formats is explained here
         *                                              http://www.php.net/manual/en/datetime.formats.php
         * @param null|string $timezone                 Timezone in which you want to set the date. Here is a list of valid
         *                                              timezones: http://php.net/manual/en/timezones.php
         *
         * @return DateTimeObject
         */
        protected static function datetime($time = "now", $timezone = null)
        {
            return new DateTimeObject($time, $timezone);
        }

        /**
         * Creates an instance of String Standard Object.
         *
         * @param string $string
         *
         * @return StringObject
         */
        protected static function str($string)
        {
            return new StringObject($string);
        }

        /**
         * Creates an instance of Url Standard Object.
         *
         * @param string $url
         *
         * @return UrlObject
         */
        protected static function url($url)
        {
            return new UrlObject($url);
        }
    }