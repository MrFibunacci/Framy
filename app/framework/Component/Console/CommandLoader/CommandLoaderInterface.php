<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\CommandLoader;

    use app\framework\Component\Console\Command\Command;
    use app\framework\Component\Console\Exception\CommandNotFoundException;

    interface CommandLoaderInterface
    {
        /**
         * Loads a Command.
         *
         * @param string $name
         * @return Command
         * @throws CommandNotFoundException
         */
        public function get($name);

        /**
         * Checks if an command exists.
         *
         * @param string $name
         * @return bool
         */
        public function has($name);

        /**
         * @return String[] All registered command names
         */
        public function getNames();
    }