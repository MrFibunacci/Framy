<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\StdLib;

    use app\framework\Component\StdLib\StdObject\StdObjectTrait;

    /**
     * Standard Library Trait
     * Some useful functions
     *
     * @package app\framework\Component\StdLib
     */
    trait StdLibTrait
    {
        use StdObjectTrait,ValidatorTrait;

        /**
         * Serializes the given value.
         *
         * @param $value
         *
         * @return string
         */
        protected static function serialize($value)
        {
            return serialize($value);
        }

        /**
         * Unserializes the given string and returns the array.
         *
         * @param string $string String to serialize.
         *
         * @return array|mixed
         */
        protected static function unserialize($string)
        {
            if (is_array($string)) {
                return $string;
            }

            if (($data = unserialize($string)) !== false) {
                return $data;
            }

            return unserialize(stripslashes($string));
        }
    }