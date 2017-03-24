<?php
/**
 * Created by IntelliJ IDEA.
 * User: MrFibunacci
 * Date: 25.12.2016
 * Time: 01:25
 */

    namespace app\framework\Component\StdLib;


    /**
     * Trait contains common validators
     *
     * @package app\framework\Component\StdLib
     */
    trait ValidatorTrait {
        protected static function is($var)
        {
            if (isset($var)) {
                return true;
            }

            return false;
        }

        /**
         * Checks if given value is null.
         *
         * @param mixed $var Value to check
         *
         * @return bool
         */
        protected static function isNull($var)
        {
            return is_null($var);
        }

        /**
         * Checks if given value is empty.
         *
         * @param mixed $var Value to check
         *
         * @return bool
         */
        protected static function isEmpty($var)
        {
            return empty($var);
        }

        /**
         * Check if given value is an object.
         *
         * @param mixed $var Value to check
         *
         * @return bool
         */
        protected static function isObject($var)
        {
            return is_object($var);
        }

        /**
         * Check if given value is a scalar value.
         * Scalar values are: integer, float, boolean and string
         *
         * @param mixed $var Value to check
         *
         * @return bool
         */
        protected static function isScalar($var)
        {
            return is_scalar($var);
        }

        /**
         * Check if given value is a resource.
         *
         * @param mixed $var Value to check
         *
         * @return bool
         */
        protected static function isResource($var)
        {
            return is_resource($var);
        }

        /**
         * Checks if given value is an array.
         *
         * @param $var
         *
         * @return bool
         */
        protected static function isArray($var)
        {
            return is_array($var);
        }

        /**
         * Checks if value is a number.
         *
         * @param $var
         *
         * @return bool
         */
        protected static function isNumber($var)
        {
            return is_numeric($var);
        }

        /**
         * Checks if value is an integer.
         *
         * @param $var
         *
         * @return bool
         */
        protected static function isInteger($var)
        {
            return is_int($var);
        }

        /**
         * Checks whenever resource is callable.
         *
         * @param $var
         *
         * @return bool
         */
        protected static function isCallable($var)
        {
            return is_callable($var);
        }

        /**
         * Checks if $var is type of string.
         *
         * @param $var
         *
         * @return bool
         */
        protected static function isString($var)
        {
            return is_string($var);
        }

        /**
         * Checks if $var is type of boolean.
         *
         * @param $var
         *
         * @return bool
         */
        protected static function isBool($var)
        {
            return is_bool($var);
        }

        /**
         * This is an alias function for self::isBool
         *
         * @param $var
         *
         * @return bool
         */
        protected static function isBoolean($var)
        {
            return self::isBool($var);
        }

        /**
         * Checks if $var is a file.
         *
         * @param $var
         *
         * @return bool
         */
        protected static function isFile($var)
        {
            return is_file($var);
        }

        /**
         * Checks if $var is readable.
         *
         * @param $var
         *
         * @return bool
         */
        protected static function isReadable($var)
        {
            return is_readable($var);
        }

        /**
         * Checks if $var is a directory.
         *
         * @param $var
         *
         * @return bool
         */
        protected static function isDirectory($var)
        {
            return is_dir($var);
        }

        /**
         * Check if $instance is of $type.
         *
         * @param mixed $instance
         * @param string $type
         *
         * @return bool
         */
        protected static function isInstanceOf($instance, $type)
        {
            return ($instance instanceof $type);
        }

        /**
         * Check if $subclass is a subclass of $class.
         *
         * @param string|object $subclass
         * @param string $class
         *
         * @return bool
         */
        protected static function isSubClassOf($subclass, $class)
        {
            return is_subclass_of($subclass, $class);
        }
    }