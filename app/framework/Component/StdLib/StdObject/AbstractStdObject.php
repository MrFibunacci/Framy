<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\StdLib\StdObject;
    use app\framework\Component\StdLib\ValidatorTrait;

    /**
     * Standard object abstract class.
     * Extend this class when you want to create your own standard object.
     *
     *
     * @package app\framework\Component\StdLib\StdObject
     */
    abstract class AbstractStdObject implements StdObjectInterface
    {
        use ValidatorTrait;

        /**
         * Return, or update, current standard objects value.
         *
         * @param null $value If $value is set, value is updated and ArrayObject is returned.
         * @return mixed
         */
        public function val($value = null)
        {
            if(is_null($value)){
                return $this->value;
            }

            $this->value = $value;
            return $this->value;
        }

        /**
         * Returns an Instance of current Object
         *
         * @return $this
         */
        protected function getObject()
        {
            return $this;
        }

        /**
         * Throw a standard object exception.
         *
         * @param $message
         *
         * @throws StdObjectException
         */
        public function exception($message)
        {
            throw new StdObjectException($message);
        }
    }