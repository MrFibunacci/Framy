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

    class Required implements ValidatorInterface
    {
        /**
         * Get validator name, eg: email
         *
         * @return string
         */
        public function getName()
        {
            return "required";
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
            if (!(is_null($value) || $value === '')) {
                return true;
            }

            $message = 'Value is Required';
            if ($throw) {
                throw new ValidationException($message);
            }

            return $message;
        }

    }