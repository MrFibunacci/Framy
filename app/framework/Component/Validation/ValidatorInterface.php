<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Validation;


    /**
     * An Interface for Validators
     *
     * @package app\framework\Component\Validation
     */
    interface ValidatorInterface {

        /**
         * Get validator name, eg: email
         *
         * @return string
         */
        public function getName();

        /**
         * Validate given value, using optional parameters and either throw an exception or return a boolean
         *
         * @param mixed     $value
         * @param array     $params
         * @param bool|true $throw
         *
         * @return boolean|string
         * @throws ValidationException
         */
        public function validate($value, $params = [], $throw = true);
    }