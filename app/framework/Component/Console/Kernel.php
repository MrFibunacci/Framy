<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console;

    use app\framework\Component\Console\Command\Command;
    use app\framework\Component\Console\Command\HelpCommand;
    use app\framework\Component\Console\Exception\CommandNotFoundException;
    use app\framework\Component\Console\Input\ArgvInput;
    use app\framework\Component\Console\Input\InputInterface;

    class Kernel
    {
        private $commands = array();
        private $singleCommand;
        private $defaultCommand;
        private $isInitialized;

        /**
         * Kernel constructor.
         *
         */
        public function __construct()
        {
            $this->defaultCommand = 'list';
            $this->isInitialized = false;


            // TODO: write command loader!
            // Load the default commands
            //$this->loadDefaultCommands();

            // and the custom made
        }

        /**
         * Execute command and show its output if required.
         *
         * @param ArgvInput $input
         */
        public function handle(ArgvInput $input)
        {
            try {
                $this->run($input);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        /**
         * Runs the current application.
         *
         * @param InputInterface $input
         * @return int 0 if everything went fine, or an error code
         *
         * @throws \Exception When running fails. Bypass this when {@link setCatchExceptions()}.
         */
        public function run(InputInterface $input = null)
        {
            if($input === null)
                $input = new ArgvInput();

            $name = $this->getCommandName($input);

            try {
                $command = $this->find($name);
            } catch (\Throwable $e) {
                //TODO: do important exception stuff
            }

            // run the current command
            $exitCode = $command->run($input);

            return $exitCode;
        }

        public function add(Command $command)
        {
            $this->init();

            if (null === $command->getDefinition()) {
                throw new \LogicException(sprintf('Command class "%s" is not correctly initialized. You probably forgot to call the parent constructor.', get_class($command)));
            }

            if (!$command->getName()) {
                throw new \LogicException(sprintf('The command defined in "%s" cannot have an empty name.', get_class($command)));
            }

            $this->commands[$command->getName()] = $command;

            /*foreach ($command->getAliases() as $alias) {
                $this->commands[$alias] = $command;
            }*/

            return $command;
        }

        /**
         * Returns true if the command exists, false otherwise.
         *
         * @param string $name The command name or alias
         *
         * @return bool true if the command exists, false otherwise
         */
        public function has($name)
        {
            $this->init();

            return isset($this->commands[$name]);
        }

        /**
         * Returns a registered command by name or alias.
         *
         * @param string $name The command name or alias
         *
         * @return Command A Command object
         *
         * @throws CommandNotFoundException When given command name does not exist
         */
        public function get($name)
        {
            $this->init();

            if(!$this->has($name))
                throw new CommandNotFoundException(sprintf('The command "%s" does not exist.', $name));

            return $this->commands[$name];
        }

        /**
         * Finds a command by name
         *
         * Contrary to get, this command tries to find the best
         * match if you give it an abbreviation of a name or alias.
         *
         * @param string $name A command name
         *
         * @return Command A Command instance
         *
         * @throws CommandNotFoundException When command name is incorrect or ambiguous
         */
        public function find($name)
        {
            //TODO: write awesome function!
        }

        /**
         * Registers a new command.
         *
         * @param string $name The command name
         *
         * @return Command The newly created command
         */
        public function register($name)
        {
            return $this->add(new Command($name));
        }

        private function getCommandName(InputInterface $input)
        {
            return $this->singleCommand ? $this->defaultCommand : $input->getFirstArgument();
        }

        /**
         * To get an array of default commands.
         *
         * @return Command[]
         */
        private function getDefaultCommands()
        {
            return [new HelpCommand()];
        }

        /*private function loadDefaultCommands()
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
        }*/

        private function init()
        {
            if($this->isInitialized)
                return;

            $this->isInitialized = true;
            foreach ($this->getDefaultCommands() as $command) {
                $this->add($command);
            }
        }
    }