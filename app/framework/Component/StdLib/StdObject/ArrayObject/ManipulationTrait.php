<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\StdLib\StdObject\ArrayObject;


    trait ManipulationTrait
    {
        /**
         * remove first element of given array
         *
         * @return $this
         */
        public function removeFirst()
        {
            $array = $this->val();

            array_shift($array);

            $this->val($array);

            return $this;
        }

        /**
         * remove last element of given array
         *
         * @return $this
         */
        public function removeLast()
        {
            $array = $this->val();

            array_pop($array);

            $this->val($array);

            return $this;
        }

        /**
         * get or update key in current Array
         *
         * @param      $key
         * @param null $value
         *
         * @return mixed
         */
        public function key($key, $value = null)
        {
            $array = $this->val();

            if(!$this->isNull($value)){
                $array[$key] = $value;
                $this->val($array);
            }

            return $array[$key];
        }

        /**
         * Inserts an element to the end of the array.
         * If you set both params, that first param is the key, and second is the value,
         * else first param is the value, and the second is ignored.
         *
         * @param mixed $k
         * @param mixed $v
         *
         * @return $this
         */
        public function append($k, $v = null)
        {
            $array = $this->val();

            if (!$this->isNull($v)) {
                $array[$k] = $v;
            } else {
                $array[] = $k;
            }

            $this->val($array);

            return $this;
        }

        /**
         * Inserts an element at the beginning of the array.
         * If you set both params, that first param is the key, and second is the value,
         * else first param is the value, and the second is ignored.
         *
         * @param mixed $k
         * @param mixed $v
         *
         * @return $this
         */
        public function prepend($k, $v = null)
        {
            $array = $this->val();

            if (!$this->isNull($v)) {
                $array = array_reverse($array, true);
                $array[$k] = $v;
                $array = array_reverse($array, true);
            } else {
                array_unshift($array, $k);
            }

            $this->val($array);

            return $this;
        }

        /**
         * remove key in current array
         *
         * @param $key
         */
        public function removeKey($key)
        {
            if ($this->keyExists($key)) {
                $array = $this->val();
                unset($array[$key]);

                $this->val($array);
            }
        }

        /**
         * Merge given $array with current array.
         *
         * @param array|ArrayObject $array
         *
         * @return $this
         */
        public function merge($array)
        {
            if($this->isInstanceOf($array, $this)){
                $array = $array->val();
            }

            $this->val(array_merge($this->val(), $array));

            return $this;
        }

        /**
         * Merge given $array with current array.
         *
         * @param array|ArrayObject $array
         *
         * @return $this
         */
        public function mergeRecursive($array)
        {
            if($this->isInstanceOf($array, $this)){
                $array = $array->val();
            }

            $this->val(array_merge_recursive($this->val(), $array));

            return $this;
        }

        /**
         * @param int $num
         *
         * @return ArrayObject
         * @throws ArrayObjectException
         */
        public function rand($num = 1)
        {
            try {
                $arr = array_rand($this->val(), $num);
            } catch (\ErrorException $e) {
                throw new ArrayObjectException($e->getMessage());
            }

            if (!$this->isArray($arr)) {
                $arr = [$arr];
            }

            return new ArrayObject($arr);
        }

        /**
         * reverse elements order
         *
         * @param bool $preserve
         *
         * @return $this
         */
        public function reverse($preserve = false)
        {
            $this->val(array_reverse($this->val(), $preserve));

            return $this;
        }

        /**
         * Shuffle elements in the array.
         *
         * @return $this
         */
        public function shuffle()
        {
            $val = $this->val();
            shuffle($val);
            $this->val($val);
            return $this;
        }

        /**
         * Removes duplicate values from an array
         *
         * @param int $sortFlag  Sorting type flags:<br>
         *                       SORT_REGULAR - compare items normally (don't change types)<br>
         *                       SORT_NUMERIC - compare items numerically<br>
         *                       SORT_STRING - compare items as strings<br>
         *                       SORT_LOCALE_STRING - compare items as strings, based on the current locale.<br>
         *
         * @return $this
         */
        public function unique($sortFlag = SORT_REGULAR)
        {
            $this->val(array_unique($this->val(), $sortFlag));

            return $this;
        }
    }