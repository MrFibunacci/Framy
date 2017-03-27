<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\StdLib\StdObject\ArrayObject;


    use app\framework\Component\StdLib\StdObject\AbstractStdObject;
    use app\framework\Component\StdLib\StdObject\ArrayObject\ManipulationTrait;
    use app\framework\Component\StdLib\StdObject\ArrayObject\ValidatorTrait;
    use app\framework\Component\StdLib\StdObject\StdObjectTrait;

    class ArrayObject extends AbstractStdObject implements \ArrayAccess, \Countable
    {
        use ValidatorTrait,ManipulationTrait,StdObjectTrait;

        protected $value;

        /**
         * @param      $value
         * @param bool $makeArrayElemObj
         */
        public function __construct($value, $makeArrayElemObj = false)
        {
            if($this->isArray($value)){
                $this->val($value);
            }
        }

        public function first()
        {
            $array = $this->val();
            $first = reset($array);

            return $first;
        }

        public function last()
        {
            $array = $this->val();
            $last = end($array);

            return $last;
        }

        /**
         * Get the sum of all elements inside the array.
         *
         * @return number The sum of all elements from within the current array.
         */
        public function sum()
        {
            return array_sum($this->val());
        }

        /**
         * To string implementation.
         *
         * @return mixed String 'Array'.
         */
        public function __toString()
        {
            return "Array";
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Whether a offset exists
         *
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
            return $this->keyExists($offset);
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Offset to retrieve
         *
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
            return $this->value[$offset];
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Offset to set
         *
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
            $this->key($offset, $value);
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Offset to unset
         *
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
            $this->removeKey($offset);
        }

        /**
         * (PHP 5 &gt;= 5.1.0)<br/>
         * Count elements of an object
         *
         * @link http://php.net/manual/en/countable.count.php
         * @return int The custom count as an integer.
         *       </p>
         *       <p>
         *       The return value is cast to an integer.
         */
        public function count()
        {
            return count($this->val());
        }
    }