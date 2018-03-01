<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\StdLib;

    use app\framework\Component\StdLib\StdObject\StdObjectWrapper;

    /**
     * Trait contains common validators
     *
     * @package app\framework\Component\StdLib
     */
    trait ValidatorTrait
    {
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
         * Checks if a string is serialized using quick string manipulation
         * to throw out obviously incorrect strings. Unserialize is then run
         * on the string to perform the final verification.
         *
         * Valid serialized forms are the following:
         * <ul>
         * <li>boolean: <code>b:1;</code></li>
         * <li>integer: <code>i:1;</code></li>
         * <li>double: <code>d:0.2;</code></li>
         * <li>string: <code>s:4:"test";</code></li>
         * <li>array: <code>a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}</code></li>
         * <li>object: <code>O:8:"stdClass":0:{}</code></li>
         * <li>null: <code>N;</code></li>
         * </ul>
         *
         * @param		string	$value	Value to test for serialized form
         * @param		mixed	$result	Result of unserialize() of the $value
         * @return		boolean	True if $value is serialized data, otherwise false
         */
        public function isSerialized($value, &$result = null)
        {
            // Bit of a give away this one
            if (!is_string($value)) {
                return false;
            }

            // Serialized false, return true. unserialize() returns false on an
            // invalid string or it could return false if the string is serialized
            // false, eliminate that possibility.
            if ($value === 'b:0;') {
                $result = false;
                return true;
            }

            $length	= strlen($value);
            $end	= '';
            switch ($value[0]) {
                case 's':
                    if ($value[$length - 2] !== '"') {
                        return false;
                    }
                case 'b':
                case 'i':
                case 'd':
                    // This looks odd but it is quicker than isset()ing
                    $end .= ';';
                case 'a':
                case 'O':
                    $end .= '}';
                    if ($value[1] !== ':') {
                        return false;
                    }
                    switch ($value[2]) {
                        case 0:
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                        case 5:
                        case 6:
                        case 7:
                        case 8:
                        case 9:
                            break;
                        default:
                            return false;
                    }
                case 'N':
                    $end .= ';';
                    if ($value[$length - 1] !== $end[0]) {
                        return false;
                    }
                    break;
                default:
                    return false;
            }

            if (($result = @unserialize($value)) === false) {
                $result = null;
                return false;
            }

            return true;
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

        /**
         * Check if $instance is a StringObject.
         *
         * @param mixed $instance
         *
         * @return bool
         */
        protected static function isStringObject($instance)
        {
            return StdObjectWrapper::isStringObject($instance);
        }

        /**
         * Check if $instance is a DateTimeObject.
         *
         * @param mixed $instance
         *
         * @return bool
         */
        protected static function isDateTimeObject($instance)
        {
            return StdObjectWrapper::isDateTimeObject($instance);
        }

        /**
         * Check if $instance is a FileObject.
         *
         * @param mixed $instance
         *
         * @return bool
         */
        protected static function isFileObject($instance)
        {
            return StdObjectWrapper::isFileObject($instance);
        }

        /**
         * Check if $instance is an ArrayObject.
         *
         * @param mixed $instance
         *
         * @return bool
         */
        protected static function isArrayObject($instance)
        {
            return StdObjectWrapper::isArrayObject($instance);
        }

        /**
         * Check if $instance is a UrlObject.
         *
         * @param mixed $instance
         *
         * @return bool
         */
        protected static function isUrlObject($instance)
        {
            return StdObjectWrapper::isUrlObject($instance);
        }
    }