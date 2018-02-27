<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Validation\Validators;

    use app\framework\Component\Validation\ValidatorInterface;
    use app\framework\Component\Validation\ValidationException;

    class MacAddress implements ValidatorInterface
    {
        /**
         * Get validator name, eg: email
         *
         * @return string
         */
        public function getName()
        {
            return "mac address";
        }

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
        public function validate($value, $params = [], $throw = true)
        {
            if (filter_var($value, FILTER_VALIDATE_MAC)) {
                return true;
            }

            $message = 'Invalid mac address';
            if ($throw) {
                throw new ValidationException($message);
            }

            return $message;
        }

    }