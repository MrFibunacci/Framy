<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Validation\Validators;


    use app\framework\Component\Validation\ValidationException;
    use app\framework\Component\Validation\ValidatorInterface;

    class MinLength implements ValidatorInterface
    {
        /**
         * Get validator name, eg: email
         *
         * @return string
         */
        public function getName()
        {
            return "min length";
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
            $limit = $params[0];
            $length = is_string($value) ? strlen($value) : count($value);

            if($length >= $limit){
                return true;
            }

            $message = "Value must be contain %s character at least";
            if($throw){
                throw new ValidationException($message, $limit);
            }

            return printf($message, $limit);
        }

    }