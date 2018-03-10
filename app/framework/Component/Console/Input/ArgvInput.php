<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Input;

    use app\framework\Component\StdLib\StdObject\ArrayObject\ArrayObject;
    use app\framework\Component\StdLib\StdObject\StdObjectTrait;

    /**
     * Class ArgvInput represents an input coming from the CLI arguments.
     *
     * @package app\framework\Component\Console
     */
    class ArgvInput extends Input
    {
        use StdObjectTrait;

        private $commandName;
        private $arguments = array();

        /**
         * ArgvInput constructor.
         *
         * @param $argv array
         */
        public function __construct(array $argv = null)
        {
            if($argv == null)
                $argv = $_SERVER['argv'];

            $argv = $this->arr($argv);
            $argv->removeFirst();

            $this->validate($argv);

            $this->commandName = $argv->first();
            $this->arguments   = $argv->removeFirst()->val();
        }

        /**
         * @return string
         */
        public function getCommandName()
        {
            return $this->commandName;
        }

        /**
         * @return array
         */
        public function getArguments()
        {
            return $this->arguments;
        }

        /**
         * throw InvalidArgumentException if not enough arguments are given
         *
         * @param &$argv ArrayObject
         */
        private function validate(&$argv)
        {
            if(!$argv->first())
                throw new \InvalidArgumentException("You have to specify an command");
        }
    }