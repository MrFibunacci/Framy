<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console;

    class Kernel
    {
        private $commands = array();

        private $defaultCommandDirectory = [
            ROOT_PATH.'/app/framework/Component/Console/BaseCommands'
        ];

        private $input;

        /**
         * Kernel constructor.
         */
        public function __construct(ArgvInput $input)
        {
            $this->input = $input;
            // Load the default commands
            $this->loadDefaultCommands();

            // and the custom made
        }

        private function loadDefaultCommands()
        {
            foreach($this->defaultCommandDirectory as $directory) {
                $files = glob($directory.'/*.php');
                foreach($files as $file) {
                    $this->load($file);
                }
            }
        }

        private function load($file)
        {
            $file = explode(ROOT_PATH."/", explode('.php', $file)[0])[1];
            $file = str_replace('/', '\\', $file);
            if(class_exists($file)) {
                $this->commands[] = new $file;
            }
        }
    }