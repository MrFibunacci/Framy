<?php

    namespace app\framework\Component\Console\CommandLoader;

    use app\framework\Component\Console\Command\Command;
    use app\framework\Component\Console\Exception\CommandNotFoundException;

    class CommandLoader implements CommandLoaderInterface
    {
        private $registeredCommands = [];

        private $defaultCommandPaths = [
            ROOT_PATH.'/app/custom/Commands'
        ];

        /**
         * CommandLoader constructor.
         */
        public function __construct()
        {
            // load Commands from default paths
            try {
                foreach($this->defaultCommandPaths as $directory) {
                    $files = glob($directory.'/*.php');
                    foreach($files as $file) {
                        $this->registerByPath($file);
                    }
                }
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }

        /**
         * Loads a Command.
         *
         * @param string $name
         * @return Command
         * @throws CommandNotFoundException
         */
        public function get($name)
        {
            if($this->has($name)) {
                return $this->registeredCommands[$name];
            } else {
                throw new CommandNotFoundException(sprintf('Command "%s" not found', $name));
            }
        }

        /**
         * Checks if an command exists.
         *
         * @param string $name
         * @return bool
         */
        public function has($name)
        {
            return isset($this->registeredCommands[$name]);
        }

        /**
         * @return String[] All registered command names
         */
        public function getNames()
        {
            return array_keys($this->registeredCommands);
        }

        /**
         * Register path of file to register Command.
         *
         * @param $path
         * @throws CommandNotFoundException
         */
        public function registerByPath($path)
        {
            $command = $this->load($path);

            if($command) {
                $this->register($command);
            } else {
                throw new CommandNotFoundException("Can't find a fucking command.");
            }
        }

        /**
         * Adds a command.
         *
         * @param Command $command
         * @return void
         */
        public function add(Command $command)
        {
            $this->registeredCommands[$command->getName()] = $command;
        }

        /**
         * adds an command to registered commands
         *
         * @param Command $command
         */
        private function register(Command $command)
        {
            // add to registered commands if not already registered.
            if(!$this->has($command->getName()))
                $this->registeredCommands[$command->getName()] = $command;
        }

        /**
         * Load command instance by path.
         *
         * @param $path
         * @return Command | false False of fail
         */
        private function load($path)
        {
            $file = explode(ROOT_PATH."/", explode('.php', $path)[0])[1];
            $file = "\\".str_replace('/', '\\', $file);

            if(class_exists($file)) {
                $tempClass = new $file;

                if($tempClass instanceof Command)
                    return new $file;
            }
            return false;
        }
    }