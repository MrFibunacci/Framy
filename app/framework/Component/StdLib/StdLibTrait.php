<?php
    namespace app\framework\Component\StdLib;
    use app\framework\Component\StdLib\ValidatorTrait;
    use app\framework\Component\StdLib\StdObject\StdObjectTrait;

    /**
     * Standard Library Trait
     * Some usefull functions
     *
     * @package app\framework\Component\StdLib
     */
    trait StdLibTrait
    {
        use StdObjectTrait,ValidatorTrait;

        /**
         * Serializes the given array.
         *
         * @param array $array Array to serialize.
         *
         * @return string
         */
        protected static function serialize(array $array)
        {
            return serialize($array);
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