<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\StdLib\Exception;

    use app\framework\Component\StdLib\ValidatorTrait;

    abstract class AbstractException extends \Exception
    {
        use ValidatorTrait;

        /**
         * Bad function call.
         */
        const MSG_BAD_FUNC_CALL = 1;

        /**
         * Bad method call.
         */
        const MSG_BAD_METHOD_CALL = 2;

        /**
         * Invalid argument provided. %s must be type of %s.
         */
        const MSG_INVALID_ARG = 3;

        /**
         * Invalid argument provided. %s must be %s.
         */
        const MSG_INVALID_ARG_LENGTH = 4;

        /**
         * Defined value for %s argument if out of the valid range.
         */
        const MSG_ARG_OUT_OF_RANGE = 5;

        /**
         * Built-in exception messages.
         * Built-in codes range from 1-100 so make sure you custom codes are out of that range.
         *
         * @var array
         */
        private static $coreMessages = [
            1 => 'Bad function call.',
            2 => 'Bad method call.',
            3 => 'Invalid argument provided. %s must be type of %s.',
            4 => 'Invalid argument provided. %s must be %s.',
            5 => 'Defined value for %s argument if out of the valid range.'
        ];

        function __construct($message, $params = null)
        {
            $code = 0;
            if($this->isNumber($message)) {
                $code = $message;
                if($code < 100) {
                    //build-in range
                    if($this->is(self::$coreMessages)) {
                        $message = self::$coreMessages[$code];
                    } else {
                        $message = 'Unknown exception message for the given code "' . $code . '".';
                    }
                } else {
                    if($this->is(static::$messages[$code])) {
                        $message = static::$messages[$code];
                    } else {
                        $message = 'Unknown exception message for the given code "' . $code . '".';
                    }
                }
            }

            if(!$this->isNull($params)) {
//                $message = $this->str($message)->format($params)->val();
            }

            parent::__construct($message, $code);
        }
    }