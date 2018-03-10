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
        /**
         * @var array
         */
        private $commands = array();

        private $defaultCommandDirectory = [
            ROOT_PATH.'/app/framework/Component/Console/Command'
        ];

        /**
         * Kernel constructor.
         *
         */
        public function __construct()
        {
            // Load the default commands
            $this->loadDefaultCommands();

            // and the custom made
        }

        /**
         * Execute command and show its output if required.
         *
         * @param ArgvInput $input
         */
        public function handle(ArgvInput $input)
        {
            foreach($this->getCommands() as $command) {
                if($command->getName() == $input->getCommandName()) {
                    echo "test\n";
                }
            }
        }

        /**
         * @return array
         */
        public function getCommands()
        {
            return $this->commands;
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