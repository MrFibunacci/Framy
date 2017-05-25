<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\StdLib\StdObject;


    /**
     * Interface StdObjectInterface
     *
     * @package app\framework\Component\StdLib\StdObject
     */
    interface StdObjectInterface
    {
        /**
         * @param $value
         */
        public function __construct($value);

        /**
         * @return mixed
         */
        public function __toString();
    }