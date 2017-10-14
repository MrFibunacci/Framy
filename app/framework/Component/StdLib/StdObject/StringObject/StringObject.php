<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\StdLib\StdObject\StringObject;

    use app\framework\Component\StdLib\StdObject\AbstractStdObject;
    use app\framework\Component\StdLib\StdObject\ArrayObject\ArrayObject;

    /**
     * String standard object.
     * This is a helper class for working with strings.
     *
     * @package         app\framework\Component\StdLib\StdObject\StringObject
     */
    class StringObject extends AbstractStdObject implements \ArrayAccess
    {
        use ManipulatorTrait, ValidatorTrait;

        /**
         * Default file encoding.
         * Used by multibyte string functions.
         */
        const DEF_ENCODING = 'UTF-8';

        /**
         * @var string
         */
        protected $value;

        /**
         * Constructor.
         * Set standard object value.
         *
         * @param string|int $value A string from which the StringObject instance will be created.
         *
         * @throws StringObjectException
         */
        public function __construct($value)
        {
            // It can be a class implementing __toString
            if (is_object($value) && method_exists($value, '__toString')) {
                $value = (string)$value;
            }

            if (!$this->isString($value) && !$this->isNumber($value)) {
                if ($this->isInstanceOf($value, $this)) {
                    return $value;
                }

                throw new StringObjectException(StringObjectException::MSG_INVALID_ARG, [
                        '$value',
                        'string'
                    ]);
            }
            $this->value = (string)$value;
        }

        /**
         * Get the length of the current string.
         *
         * @return int Length of current string.
         */
        public function length()
        {
            return mb_strlen($this->val(), self::DEF_ENCODING);
        }

        /**
         * Get the number of words in the string.
         *
         * @param int $format Specify the return format:
         *                    0 - return number of words
         *                    1 - return an ArrayObject containing all the words found inside the string
         *                    2 - returns an ArrayObject, where the key is the numeric position of the word
         *                    inside the string and the value is the actual word itself
         *
         * @return mixed|ArrayObject An ArrayObject or integer, based on the wanted $format, with the stats about the words in the string.
         */
        public function wordCount($format = 0)
        {
            if ($format < 1) {
                return str_word_count($this->val(), $format);
            } else {
                return new ArrayObject(str_word_count($this->val(), $format));
            }

        }

        /**
         * To string implementation.
         *
         * @return string Current string.
         */
        public function __toString()
        {
            return $this->val();
        }

        /**
         * Get number of string occurrences in current string.
         *
         * @param string   $string String to search for
         * @param null|int $offset The offset where to start counting
         * @param null|int $length The maximum length after the specified offset to search for the substring.
         *                         It outputs a warning if the offset plus the length is greater than the haystack length.
         *
         * @return int
         */
        public function subStringCount($string, $offset = 0, $length = null)
        {
            if ($this->isNull($length)) {
                $length = $this->length();
            }

            return substr_count($this->val(), $string, $offset, $length);
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Whether a offset exists
         * @link http://php.net/manual/en/arrayaccess.offsetexists.php
         *
         * @param mixed $offset <p>
         *                      An offset to check for.
         *                      </p>
         *
         * @return boolean true on success or false on failure.
         * </p>
         * <p>
         * The return value will be casted to boolean if non-boolean was returned.
         */
        public function offsetExists($offset)
        {
            return $this->length() >= $offset;
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Offset to retrieve
         * @link http://php.net/manual/en/arrayaccess.offsetget.php
         *
         * @param mixed $offset <p>
         *                      The offset to retrieve.
         *                      </p>
         *
         * @return mixed Can return all value types.
         */
        public function offsetGet($offset)
        {
            return $this->val()[$offset];
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Offset to set
         * @link http://php.net/manual/en/arrayaccess.offsetset.php
         *
         * @param mixed $offset <p>
         *                      The offset to assign the value to.
         *                      </p>
         * @param mixed $value  <p>
         *                      The value to set.
         *                      </p>
         *
         * @return void
         */
        public function offsetSet($offset, $value)
        {
            $this->val()[$offset] = $value;
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Offset to unset
         * @link http://php.net/manual/en/arrayaccess.offsetunset.php
         *
         * @param mixed $offset <p>
         *                      The offset to unset.
         *                      </p>
         *
         * @return void
         */
        public function offsetUnset($offset)
        {
            // Do nothing
        }
    }