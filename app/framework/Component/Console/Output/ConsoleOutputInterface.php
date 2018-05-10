<?php
/**
 *
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Output;

    /**
     * ConsoleOutputInterface is the interface implemented by ConsoleOutput class.
     * This adds information about stderr and section output stream.
     *
     * @package app\framework\Component\Console\Output
     */
    interface ConsoleOutputInterface
    {
        /**
         * Gets the OutputInterface for errors.
         *
         * @return OutputInterface
         */
        public function getErrorOutput();

        public function setErrorOutput(OutputInterface $error);

    }