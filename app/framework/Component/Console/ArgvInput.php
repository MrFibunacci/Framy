<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console;

    /**
     * Class ArgvInput represents an input coming from the CLI arguments.
     *
     * @package app\framework\Component\Console
     */
    class ArgvInput
    {
        /**
         * Contains the called command.
         *
         * @var
         */
        private $command;

        /**
         * Contains all arguments as an array.
         *
         * @var array
         */
        private $arguments = array();

        /**
         * ArgvInput constructor.
         */
        public function __construct(array $argv = null)
        {
            //var_dump($_SERVER['argv']);
        }

        private function validate()
        {
            //TODO: throw InvalidArgumentException when not enough arguments are given
        }
    }