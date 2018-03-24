<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console;

    use app\framework\Component\Console\Command\Command;
    use app\framework\Component\Console\Command\ListCommand;
    use app\framework\Component\Console\CommandLoader\CommandLoader;
    use app\framework\Component\Console\Command\HelpCommand;
    use app\framework\Component\Console\Exception\CommandNotFoundException;
    use app\framework\Component\Console\Helper\Helper;
    use app\framework\Component\Console\Input\ArgvInput;
    use app\framework\Component\Console\Input\InputInterface;
    use app\framework\Component\Console\Output\ConsoleOutput;
    use app\framework\Component\Console\Output\Formatter\OutputFormatter;
    use app\framework\Component\Console\Output\OutputInterface;

    class Kernel
    {
        private $commands = array();
        private $singleCommand;
        private $defaultCommand;
        private $isInitialized;
        private $commandLoader;
        private $catchExceptions = true;
        private $terminal;

        /**
         * Kernel constructor.
         *
         */
        public function __construct()
        {
            $this->defaultCommand = 'list';
            $this->isInitialized = false;

            try {
                $this->commandLoader = new CommandLoader();
                $this->terminal = new Terminal();
            } catch (\Exception $e) {
                echo "Some shit went wrong: ".$e->getMessage()."\n";
            }
        }

        /**
         * Execute command and show its output if required.
         *
         * @param ArgvInput       $input
         * @param OutputInterface $output
         */
        public function handle(ArgvInput $input, OutputInterface $output)
        {
            try {
                $this->run($input, $output);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        /**
         * Runs the current application.
         *
         * @param InputInterface  $input
         * @param OutputInterface $output
         * @return int 0 if everything went fine, or an error code
         *
         * @throws \Exception When running fails. Bypass this when {@link setCatchExceptions()}.
         */
        public function run(InputInterface $input = null, OutputInterface $output = null)
        {
            if($input === null)
                $input = new ArgvInput();

            if($output === null)
                $output = new ConsoleOutput();

            $name = $this->getCommandName($input);

            // if no command in called, call default command
            if($name == null)
                $name = $this->defaultCommand;

            /*$renderException = function ($e) use ($output) {
                if (!$e instanceof \Exception) {
                        $e = class_exists(FatalThrowableError::class) ? new FatalThrowableError($e) : new \ErrorException($e->getMessage(), $e->getCode(), E_ERROR, $e->getFile(), $e->getLine());
                }

                if ($output instanceof ConsoleOutputInterface) {
                    $this->renderException($e, $output->getErrorOutput());
                } else {
                    $this->renderException($e, $output);
                }
            };

            if ($phpHandler = set_exception_handler($renderException)) {
                restore_exception_handler();
                if ($debugHandler = $phpHandler[0]->setExceptionHandler($renderException)) {
                    $phpHandler[0]->setExceptionHandler($debugHandler);
                }
            }*/

            $this->configureIO($input, $output);

            try {
                $command = $this->find($name);

                // run the current command
                $exitCode = $command->run($input, $output);
            } catch (\Exception $e) {
                if (!$this->catchExceptions) {
                    throw $e;
                }

                // temp: some shit went wrong
                $exitCode = 1;
                echo "oh sheet some error happened:\n";
                echo $e->getMessage(); echo "\n";
            }

            return $exitCode;
        }

        public function add(Command $command)
        {
            $this->init();

            $command->setKernel($this);

            if (!$command->isEnabled()) {
                $command->setKernel(null);

                return;
            }

            if ($command->getDefinition() === null) {
                throw new \LogicException(sprintf('Command class "%s" is not correctly initialized. You probably forgot to call the parent constructor.', get_class($command)));
            }

            if (!$command->getName()) {
                throw new \LogicException(sprintf('The command defined in "%s" cannot have an empty name.', get_class($command)));
            }

            $this->commands[$command->getName()] = $command;

            return $command;
        }

        /**
         * Configures the input and output instances based on the user arguments and options.
         * @param InputInterface  $input
         * @param OutputInterface $output
         */
        protected function configureIO(InputInterface $input, OutputInterface $output)
        {
            if (true === $input->hasParameterOption(array('--ansi'), true)) {
                $output->setDecorated(true);
            } elseif (true === $input->hasParameterOption(array('--no-ansi'), true)) {
                $output->setDecorated(false);
            }

            if (true === $input->hasParameterOption(array('--no-interaction', '-n'), true)) {
                $input->setInteractive(false);
            }

            switch ($shellVerbosity = (int) getenv('SHELL_VERBOSITY')) {
                case -1: $output->setVerbosity(OutputInterface::VERBOSITY_QUIET); break;
                case 1: $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE); break;
                case 2: $output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE); break;
                case 3: $output->setVerbosity(OutputInterface::VERBOSITY_DEBUG); break;
                default: $shellVerbosity = 0; break;
            }

            if (true === $input->hasParameterOption(array('--quiet', '-q'), true)) {
                $output->setVerbosity(OutputInterface::VERBOSITY_QUIET);
                $shellVerbosity = -1;
            } else {
                if ($input->hasParameterOption('-vvv', true) || $input->hasParameterOption('--verbose=3', true) || 3 === $input->getParameterOption('--verbose', false, true)) {
                    $output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
                    $shellVerbosity = 3;
                } elseif ($input->hasParameterOption('-vv', true) || $input->hasParameterOption('--verbose=2', true) || 2 === $input->getParameterOption('--verbose', false, true)) {
                    $output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
                    $shellVerbosity = 2;
                } elseif ($input->hasParameterOption('-v', true) || $input->hasParameterOption('--verbose=1', true) || $input->hasParameterOption('--verbose', true) || $input->getParameterOption('--verbose', false, true)) {
                    $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
                    $shellVerbosity = 1;
                }
            }

            if (-1 === $shellVerbosity)
                $input->setInteractive(false);

            putenv('SHELL_VERBOSITY='.$shellVerbosity);
            $_ENV['SHELL_VERBOSITY'] = $shellVerbosity;
            $_SERVER['SHELL_VERBOSITY'] = $shellVerbosity;
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

            return isset($this->commands[$name]) && $this->commandLoader->has($name);
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

            if(!$this->has($name) && $this->commandLoader->has($name))
                throw new CommandNotFoundException(sprintf('The command "%s" does not exist.', $name));

            if(isset($this->commands[$name]))
                return $this->commands[$name];

            return $this->commandLoader->get($name);
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
            //TODO: implement: Contrary to get, this command tries to find the best match if you give it an abbreviation of a name or alias.
            return $this->get($name);
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
            return [new HelpCommand(), new ListCommand()];
        }

        /**
         * Sets whether to catch exceptions or not during commands execution.
         *
         * @param bool $boolean Whether to catch exceptions or not during commands execution
         */
        public function setCatchExceptions($boolean)
        {
            $this->catchExceptions = (bool) $boolean;
        }

        private function init()
        {
            if($this->isInitialized)
                return;

            $this->isInitialized = true;
            foreach ($this->getDefaultCommands() as $command) {
                $this->add($command);
            }
        }

        /**
         * Renders a caught exception.
         */
        public function renderException(\Exception $e, OutputInterface $output)
        {
            $output->writeln('', OutputInterface::VERBOSITY_QUIET);
            $this->doRenderException($e, $output);

            if (null !== $this->runningCommand) {
                $output->writeln(sprintf('<info>%s</info>', sprintf($this->runningCommand->getSynopsis(), $this->getName())), OutputInterface::VERBOSITY_QUIET);
                $output->writeln('', OutputInterface::VERBOSITY_QUIET);
            }
        }

        protected function doRenderException(\Exception $e, OutputInterface $output)
        {
            do {
                $message = trim($e->getMessage());

                if ('' === $message || OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                    $title = sprintf('  [%s%s]  ', get_class($e), 0 !== ($code = $e->getCode()) ? ' ('.$code.')' : '');
                    $len = Helper::strlen($title);
                } else {
                    $len = 0;
                }

                $width = $this->terminal->getWidth() ? $this->terminal->getWidth() - 1 : PHP_INT_MAX;
                $lines = array();

                foreach ('' !== $message ? preg_split('/\r?\n/', $message) : array() as $line) {
                    foreach ($this->splitStringByWidth($line, $width - 4) as $line) {
                        // pre-format lines to get the right string length
                        $lineLength = Helper::strlen($line) + 4;
                        $lines[] = array($line, $lineLength);
                        $len = max($lineLength, $len);
                    }
                }

                $messages = array();

                if (!$e instanceof ExceptionInterface || OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                    $messages[] = sprintf('<comment>%s</comment>', OutputFormatter::escape(sprintf('In %s line %s:', basename($e->getFile()) ?: 'n/a', $e->getLine() ?: 'n/a')));
                }

                $messages[] = $emptyLine = sprintf('<error>%s</error>', str_repeat(' ', $len));

                if ('' === $message || OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                    $messages[] = sprintf('<error>%s%s</error>', $title, str_repeat(' ', max(0, $len - Helper::strlen($title))));
                }

                foreach ($lines as $line) {
                    $messages[] = sprintf('<error>  %s  %s</error>', OutputFormatter::escape($line[0]), str_repeat(' ', $len - $line[1]));
                }

                $messages[] = $emptyLine;
                $messages[] = '';
                $output->writeln($messages, OutputInterface::VERBOSITY_QUIET);

                if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                    $output->writeln('<comment>Exception trace:</comment>', OutputInterface::VERBOSITY_QUIET);
                    // exception related properties
                    $trace = $e->getTrace();

                    for ($i = 0, $count = count($trace); $i < $count; ++$i) {
                        $class = isset($trace[$i]['class']) ? $trace[$i]['class'] : '';
                        $type = isset($trace[$i]['type']) ? $trace[$i]['type'] : '';
                        $function = $trace[$i]['function'];
                        $file = isset($trace[$i]['file']) ? $trace[$i]['file'] : 'n/a';
                        $line = isset($trace[$i]['line']) ? $trace[$i]['line'] : 'n/a';
                        $output->writeln(sprintf(' %s%s%s() at <info>%s:%s</info>', $class, $type, $function, $file, $line), OutputInterface::VERBOSITY_QUIET);
                    }
                    $output->writeln('', OutputInterface::VERBOSITY_QUIET);
                }
            } while ($e = $e->getPrevious());
        }
    }