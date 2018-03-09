<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console;

    /**
     * Base command class.
     *
     * @package app\framework\Component\Console
     */
    class Command
    {
        /**
         * Name and signature of the command.
         *
         * @var string
         */
        protected $signature;

        /**
         * Description of the command.
         *
         * @var string
         */
        protected $description;

        /**
         * Help text of the command.
         *
         * @var string
         */
        protected $help;

        /**
         * Command constructor.
         */
        public function __construct($name = null)
        {

        }

        public function execute()
        {

        }
    }