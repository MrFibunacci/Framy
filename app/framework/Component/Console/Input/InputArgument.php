<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Input;

    /**
     * Represents a command line argument.
     *
     * @package app\framework\Component\Console\Input
     */
    class InputArgument
    {
        const REQUIRED = 1;
        const OPTIONAL = 2;
        const IS_ARRAY = 4;

        private $name;
        private $mode;
        private $default;
        private $description;

        /**
         * InputArgument constructor.
         * @param $name
         * @param $mode
         * @param $default
         * @param $description
         */
        public function __construct(string $name, int $mode = null, string $description= '', $default = null)
        {
            if($mode === null) {
                $mode = self::OPTIONAL;
            } elseif($mode > 7 || $mode < 1) {
                throw new \InvalidArgumentException(sprintf('Argument mode "%s" is not valid', $mode));
            }

            $this->name = $name;
            $this->mode = $mode;
            $this->description = $description;

            $this->setDefault($default);
        }

        /**
         * Returns true if the option requires a value.
         *
         * @return bool true if mode is self::VALUE_REQUIRED, otherwise false
         */
        public function isRequired()
        {
            return self::REQUIRED === (self::REQUIRED & $this->mode);
        }

        /**
         * Returns true if the option takes a optional value.
         *
         * @return bool true if mode is self::VALUE_OPTIONAL, otherwise false
         */
        public function isOptional()
        {
            return self::OPTIONAL === (self::OPTIONAL & $this->mode);
        }

        /**
         * Returns true if the option can take multiple values.
         *
         * @return bool true if mode is self::VALUE_IS_ARRAY, false otherwise.
         */
        public function isArray()
        {
            return self::IS_ARRAY === (self::IS_ARRAY & $this->mode);
        }

        /**
         * Returns the name.
         *
         * @return mixed
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * Returns the default value.
         *
         * @return mixed
         */
        public function getDefault()
        {
            return $this->default;
        }

        /**
         * Returns the description text.
         *
         * @return string
         */
        public function getDescription()
        {
            return $this->description;
        }

        /**
         * Sets the default value.
         *
         * @param mixed $default
         */
        public function setDefault($default = null)
        {
            if(self::REQUIRED === $this->mode && null !== $default)
                throw new \LogicException('Cannot set default value except for InputArgument::OPTIONAL mode.');

            if($this->isArray()) {
                if(null === $default) {
                    $default = array();
                } elseif(!is_array($default)) {
                    throw new \LogicException('A default value for an array option must be an array.');
                }
            }

            $this->default = $default;
        }
    }