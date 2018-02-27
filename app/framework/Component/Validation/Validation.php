<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Validation;

    use app\framework\Component\StdLib\SingletonTrait;
    use app\framework\Component\Validation\Validators\CreditCardNumber;
    use app\framework\Component\Validation\Validators\Email;
    use app\framework\Component\Validation\Validators\GeoLocation;
    use app\framework\Component\Validation\Validators\GreaterThan;
    use app\framework\Component\Validation\Validators\GreaterThanOrEqual;
    use app\framework\Component\Validation\Validators\Integer;
    use app\framework\Component\Validation\Validators\IP;
    use app\framework\Component\Validation\Validators\LessThan;
    use app\framework\Component\Validation\Validators\LessThanOrEqual;
    use app\framework\Component\Validation\Validators\MacAddress;
    use app\framework\Component\Validation\Validators\MaxLength;
    use app\framework\Component\Validation\Validators\MinLength;
    use app\framework\Component\Validation\Validators\Number;
    use app\framework\Component\Validation\Validators\Password;
    use app\framework\Component\Validation\Validators\Phone;
    use app\framework\Component\Validation\Validators\Required;
    use app\framework\Component\Validation\Validators\URL;

    /**
     * Validation component main class
     *
     * @package app\framework\Component\Validation
     */
    class Validation
    {
        use SingletonTrait;

        private $validators = [];

        /**
        * @param mixed        $data
        * @param string|array $validators
        * @param bool|true    $throw
        *
        * @return bool
        * @throws ValidationException
        */
        public function validate($data, $validators, $throw = true)
        {
            $validators = $this->getValidators($validators);
            foreach ($validators as $validator) {
                $validator = explode(':', $validator);

                $functionParams = [
                    $data,
                    array_splice($validator, 1),
                    $throw
                ];

                if (!array_key_exists($validator[0], $this->validators)) {
                    throw new ValidationException('Validator %s does not exist!', $validator[0]);
                }
                $res = $this->validators[$validator[0]]->validate(...$functionParams);

                // If validation failed and we are not throwing, return error message string
                if ($res !== true && !$throw) {
                    return $res;
                }
            }

            return true;
        }

        /**
         * Add validator to Validation component
         *
         * @param ValidatorInterface $validator
         *
         * @return $this
         */
        public function addValidator(ValidatorInterface $validator)
        {
            $this->validators[$validator->getName()] = $validator;

            return $this;
        }

        public function init()
        {
            $buildInValidators = [
                new Email(),
                new URL(),
                new Number(),
                new Integer(),
                new IP(),
                new Required(),
                new MacAddress(),
                new Phone(),
                new Password(),
                new GreaterThan(),
                new GreaterThanOrEqual(),
                new LessThan(),
                new LessThanOrEqual(),
                new MinLength(),
                new MaxLength(),
                new CreditCardNumber(),
                new GeoLocation()
            ];

            /* @var $v ValidatorInterface */
            foreach($buildInValidators as $v){
                $this->validators[$v->getName()] = $v;
            }
        }

        private function getValidators($validators)
        {
            if (is_array($validators)) {
                return $validators;
            }

            if (is_string($validators)) {
                return explode(',', $validators);
            }

            return [];
        }
    }
