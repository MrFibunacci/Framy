<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Input;

    /**
     * Represents a command line option.
     *
     * @package app\framework\Component\Console\Input
     */
    class InputOption
    {
        const VALUE_NONE = 1;
        const VALUE_REQUIRED = 2;
        const VALUE_OPTIONAL = 4;
        const VALUE_IS_ARRAY = 8;

        private $name;
        private $mode;
        private $default;
        private $description;

        /**
         * InputOption constructor.
         * @param $name
         * @param $mode
         * @param $default
         * @param $description
         */
        public function __construct($name,int $mode = null, $default = null, string $description = '')
        {
            if(empty($name))
                throw new \InvalidArgumentException('An option name cannot be empty.');

            if(null === $mode) {
                $mode = self::VALUE_NONE;
            } elseif($mode > 15 || $mode < 1) {
                throw new \InvalidArgumentException(sprintf('Option mode "%s" is not valid.', $mode));
            }

            $this->name = $name;
            $this->mode = $mode;
            $this->description = $description;
        }

        /**
         * Returns true if the option accepts a value.
         *
         * @return bool true if value mode is not self::VALUE_NONE, false otherwise
         */
        public function acceptValue()
        {
            return $this->isValueRequired() || $this->isValueOptional();
        }

        /**
         * Returns true if the option requires a value.
         *
         * @return bool true if mode is self::VALUE_REQUIRED, otherwise false
         */
        public function isValueRequired()
        {
            return self::VALUE_REQUIRED === (self::VALUE_REQUIRED & $this->mode);
        }

        /**
         * Returns true if the option takes a optional value.
         *
         * @return bool true if mode is self::VALUE_OPTIONAL, otherwise false
         */
        public function isValueOptional()
        {
            return self::VALUE_OPTIONAL === (self::VALUE_OPTIONAL& $this->mode);
        }

        /**
         * Returns true if the option can take multiple values.
         *
         * @return bool true if mode is self::VALUE_IS_ARRAY, false otherwise.
         */
        public function isArray()
        {
            return self::VALUE_IS_ARRAY === (self::VALUE_IS_ARRAY & $this->mode);
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
            if(self::VALUE_NONE === (self::VALUE_NONE & $this->mode) && null !== $default)
                throw new \LogicException('Cannot set default value when using InputOption::VALUE_NONE mode.');

            if($this->isArray()) {
                if(null === $default) {
                    $default = array();
                } elseif(!is_array($default)) {
                    throw new \LogicException('A default value for an array option must be an array.');
                }
            }

            $this->default = $this->acceptValue() ? $default : false;
        }

        /**
         * Checks whether the given option equals this one.
         *
         * @param InputOption $option
         * @return bool
         */
        public function equals(InputOption $option)
        {
            return $option->getName() === $this->getName()
                && $option->getDefault() === $this->getDefault()
                && $option->getDescription() === $this->getDescription()
                && $option->isArray() === $this->isArray()
                && $option->isValueOptional() === $this->isValueOptional()
                && $option->isValueRequired() === $this->isValueRequired()
            ;
        }
    }