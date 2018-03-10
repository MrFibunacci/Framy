<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Command;

    /**
     * Base command class.
     *
     * @package app\framework\Component\Console
     */
    class Command
    {
        /**
         * Name of the command
         *
         * @var string
         */
        private $name;

        /**
         * signature of the command.
         *
         * @var string
         */
        private $signature;

        /**
         * Description of the command.
         *
         * @var string
         */
        private $description;

        /**
         * Help text of the command.
         *
         * @var string
         */
        private $help;

        private $code;

        /**
         * Command constructor.
         */
        public function __construct()
        {

        }

        protected function execute()
        {
            throw new \LogicException('You must override the execute() method in the concrete command class.');
        }

        /**
         * Runs the command.
         *
         * The code to execute is either defined directly with the
         * setCode() method or by overriding the execute() method in a sub-class.
         *
         * @param $input
         */
        public function run($input)
        {

        }

        /**
         * Sets the code to execute when running this command.
         *
         * If this method is used, it ov
         *
         * @param callable $code A callable
         * @return $this
         * @throws \ReflectionException
         * @see execute()
         */
        public function setCode(callable $code)
        {
            if($code instanceof \Closure) {
                $r = new \ReflectionFunction($code);
                if(null === $r->getClosureThis()) {
                    $code = \Closure::bind($code, $this);
                }
            }

            $this->code = $code;

            return $this;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param string $name
         */
        public function setName($name)
        {
            $this->name = $name;
        }

        /**
         * @return string
         */
        public function getSignature()
        {
            return $this->signature;
        }

        /**
         * @param string $signature
         */
        public function setSignature($signature)
        {
            $this->signature = $signature;
        }

        /**
         * @return string
         */
        public function getDescription()
        {
            return $this->description;
        }

        /**
         * @param string $description
         */
        public function setDescription($description)
        {
            $this->description = $description;
        }

        /**
         * @return string
         */
        public function getHelp()
        {
            return $this->help;
        }

        /**
         * @param string $help
         */
        public function setHelp($help)
        {
            $this->help = $help;
        }

        /**
         * Validates a command name
         *
         * It must be non-empty and parts can optionally be separated by ":".
         *
         * @param string $name
         * @throws \InvalidArgumentException When the name is invalid
         */
        private function validateName($name)
        {
            if(!preg_match('/^[^\:]++(\:[^\:]++)*$/', $name)) {
                throw new \InvalidArgumentException(sprintf('Command name "%s" is invalid.', $name));
            }
        }
    }