<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Validation\Validators;


    use app\framework\Component\Validation\ValidatorInterface;

    class Phone implements ValidatorInterface
    {
        /**
         * Get validator name, eg: email
         *
         * @return string
         */
        public function getName()
        {
            return "phone";
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
            if (preg_match_all("/^[-+0-9()]+$/", $value)) {
                return true;
            }

            $message = 'Value must be a valid phone number';
            if ($throw) {
                throw new ValidationException($message);
            }

            return $message;
        }

    }