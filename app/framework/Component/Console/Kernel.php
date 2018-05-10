<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console;

    use app\framework\Component\Console\Command\Command;
    use app\framework\Component\Console\Command\FramyVersionCommand;
    use app\framework\Component\Console\Command\ListCommand;
    use app\framework\Component\Console\Command\NewCommand;
    use app\framework\Component\Console\Command\NewController;
    use app\framework\Component\Console\CommandLoader\CommandLoader;
    use app\framework\Component\Console\Command\HelpCommand;
    use app\framework\Component\Console\Exception\CommandNotFoundException;
    use app\framework\Component\Console\Exception\NamespaceNotFoundException;
    use app\framework\Component\Console\Helper\Helper;
    use app\framework\Component\Console\Input\ArgvInput;
    use app\framework\Component\Console\Input\InputArgument;
    use app\framework\Component\Console\Input\InputDefinition;
    use app\framework\Component\Console\Input\InputInterface;
    use app\framework\Component\Console\Input\InputOption;
    use app\framework\Component\Console\Output\ConsoleOutput;
    use app\framework\Component\Console\Output\Formatter\OutputFormatter;
    use app\framework\Component\Console\Output\OutputInterface;

    class Kernel
    {
        //private $commands = array();
        private $commandLoader;
        private $singleCommand;
        private $defaultCommand;
        private $definition;
        private $isInitialized;
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

            //TODO: check if this try and catch block is necessary:
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

                //TODO: make this more beautiful with methods like doRenderException
                // temp: some shit went wrong
                $exitCode = 1;
                echo "oh sheet some error happened:\n";
                echo $e->getMessage(); echo "\n";
            }

            return $exitCode;
        }

        /**
         * Adds a command.
         *
         * @param  Command $command
         * @return Command | void  Void if command is not enabled;
         * @throws \LogicException if something is not correct with the Command
         */
        public function add(Command $command)
        {
            $this->init();

            $command->setKernel($this);

            if (!$command->isEnabled()) {
                $command->setKernel(null);

                return;
            }

            if ($command->getDefinition() === null)
                throw new \LogicException(sprintf('Command class "%s" is not correctly initialized. You probably forgot to call the parent constructor.', get_class($command)));

            if (!$command->getName())
                throw new \LogicException(sprintf('The command defined in "%s" cannot have an empty name.', get_class($command)));

            //$this->commands[$command->getName()] = $command;
            $this->commandLoader->add($command);

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

            return isset($this->commands[$name]) || $this->commandLoader->has($name);
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
            $this->init();

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

        /**
         * Sets whether to catch exceptions or not during commands execution.
         *
         * @param bool $boolean Whether to catch exceptions or not during commands execution
         */
        public function setCatchExceptions($boolean)
        {
            $this->catchExceptions = (bool) $boolean;
        }

        /**
         * Renders a caught exception.
         *
         * @param \Exception $e
         * @param OutputInterface $output
         */
        public function renderException(\Exception $e, OutputInterface $output)
        {
            $output->writeln('', OutputInterface::VERBOSITY_QUIET);
            $this->doRenderException($e, $output);

            if (null !== $this->runningCommand) {
                $output->writeln(sprintf('<info>%s</info>', $this->runningCommand->getSynopsis()), OutputInterface::VERBOSITY_QUIET);
                $output->writeln('', OutputInterface::VERBOSITY_QUIET);
            }
        }

        /**
         * Gets the commands (registered in the given namespace if provided).
         *
         * The array keys are the full names and the values the command instances.
         *
         * @param string $namespace A namespace name
         *
         * @return Command[] An array of Command instances
         */
        public function all($namespace = null)
        {
            $this->init();

            if($namespace == null) {
                if(!$this->commandLoader)
                    return $this->commands;

                $commands = array();
                foreach($this->commandLoader->getNames() as $name) {
                    if (!isset($commands[$name]) && $this->has($name)) {
                        $commands[$name] = $this->get($name);
                    }
                }

                return $commands;
            }

            $commands = array();
            foreach ($this->commands as $name => $command) {
                if ($namespace === $this->extractNamespace($name, substr_count($namespace, ':') + 1)) {
                    $commands[$name] = $command;
                }
            }

            if ($this->commandLoader) {
                foreach ($this->commandLoader->getNames() as $name) {
                    if (!isset($commands[$name]) && $namespace === $this->extractNamespace($name, substr_count($namespace, ':') + 1) && $this->has($name)) {
                        $commands[$name] = $this->get($name);
                    }
                }
            }

            return $commands;
        }

        /**
         * Returns the namespace part of the command name.
         *
         * This method is not part of public API and should not be used directly.
         *
         * @param string $name  The full name of the command
         * @param string $limit The maximum number of parts of the namespace
         *
         * @return string The namespace of the command
         */
        public function extractNamespace($name, $limit = null)
        {
            $parts = explode(':', $name);
            array_pop($parts);

            return implode(':', null === $limit ? $parts : array_slice($parts, 0, $limit));
        }

        /**
         * Finds a registered namespace by a name or an abbreviation.
         *
         * @param string $namespace A namespace or abbreviation to search for
         *
         * @return string A registered namespace
         *
         * @throws NamespaceNotFoundException When namespace is incorrect or ambiguous
         */
        public function findNamespace($namespace)
        {
            $allNamespaces = $this->getNamespaces();
            $expr          = preg_replace_callback('{([^:]+|)}', function ($matches) { return preg_quote($matches[1]).'[^:]*'; }, $namespace);
            $namespaces    = preg_grep('{^'.$expr.'}', $allNamespaces);

            if (empty($namespaces)) {
                $message = sprintf('There are no commands defined in the "%s" namespace.', $namespace);
                if ($alternatives = $this->findAlternatives($namespace, $allNamespaces)) {
                    if (1 == count($alternatives)) {
                        $message .= "\n\nDid you mean this?\n    ";
                    } else {
                        $message .= "\n\nDid you mean one of these?\n    ";
                    }
                    $message .= implode("\n    ", $alternatives);
                }

                throw new NamespaceNotFoundException($message, $alternatives);
            }

            $exact = in_array($namespace, $namespaces, true);
            if (count($namespaces) > 1 && !$exact)
                throw new NamespaceNotFoundException(sprintf("The namespace \"%s\" is ambiguous.\nDid you mean one of these?\n%s", $namespace, $this->getAbbreviationSuggestions(array_values($namespaces))), array_values($namespaces));

            return $exact ? $namespace : reset($namespaces);
        }

        /**
         * Returns an array of all unique namespaces used by currently registered commands.
         *
         * It does not return the global namespace which always exists.
         *
         * @return string[] An array of namespaces
         */
        public function getNamespaces()
        {
            $namespaces = array();

            foreach ($this->all() as $command) {
                $namespaces = array_merge($namespaces, $this->extractAllNamespaces($command->getName()));
                foreach ($command->getAliases() as $alias) {
                    $namespaces = array_merge($namespaces, $this->extractAllNamespaces($alias));
                }
            }

            return array_values(array_unique(array_filter($namespaces)));
        }

        /**
         * Returns the long version.
         *
         * @return string The long application version
         */
        public function getLongVersion()
        {
            /*if ('UNKNOWN' !== $this->getName()) {
                if ('UNKNOWN' !== $this->getVersion()) {
                    return sprintf('%s <info>%s</info>', $this->getName(), $this->getVersion());
                }
                return $this->getName();
            }*/

            return 'Console Tool';
        }

        /**
         * Gets the help message.
         *
         * @return string A help message
         */
        public function getHelp()
        {
            return $this->getLongVersion();
        }

        /**
         * Gets the InputDefinition related to this Application.
         *
         * @return InputDefinition The InputDefinition instance
         */
        public function getDefinition()
        {
            if (!$this->definition)
                $this->definition = $this->getDefaultInputDefinition();

            if ($this->singleCommand) {
                $inputDefinition = $this->definition;
                $inputDefinition->setArguments();

                return $inputDefinition;
            }

            return $this->definition;
        }

        public function setDefinition(InputDefinition $definition)
        {
            $this->definition = $definition;
        }

        /**
         * Get name of command from Input.
         *
         * @param InputInterface $input
         * @return null|string
         */
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
            return [new HelpCommand(), new ListCommand(), new FramyVersionCommand(), new NewCommand(), new NewController()];
        }

        /**
         * If is not yet initialized add default commands.
         *
         * @return void
         */
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
         * Returns all namespaces of the command name.
         *
         * @param string $name The full name of the command
         *
         * @return string[] The namespaces of the command
         */
        private function extractAllNamespaces($name)
        {
            // -1 as third argument is needed to skip the command short name when exploding
            $parts = explode(':', $name, -1);
            $namespaces = array();

            foreach ($parts as $part) {
                if (count($namespaces)) {
                    $namespaces[] = end($namespaces).':'.$part;
                } else {
                    $namespaces[] = $part;
                }
            }

            return $namespaces;
        }

        /**
         * Finds alternative of $name among $collection,
         * if nothing is found in $collection, try in $abbrevs.
         *
         * @param string   $name       The string
         * @param iterable $collection The collection
         *
         * @return string[] A sorted array of similar string
         */
        private function findAlternatives($name, $collection)
        {
            $threshold = 1e3;
            $alternatives = array();
            $collectionParts = array();

            foreach ($collection as $item) {
                $collectionParts[$item] = explode(':', $item);
            }

            foreach (explode(':', $name) as $i => $subname) {
                foreach ($collectionParts as $collectionName => $parts) {
                    $exists = isset($alternatives[$collectionName]);

                    if (!isset($parts[$i]) && $exists) {
                        $alternatives[$collectionName] += $threshold;
                        continue;
                    } elseif (!isset($parts[$i])) {
                        continue;
                    }

                    $lev = levenshtein($subname, $parts[$i]);

                    if ($lev <= strlen($subname) / 3 || '' !== $subname && false !== strpos($parts[$i], $subname)) {
                        $alternatives[$collectionName] = $exists ? $alternatives[$collectionName] + $lev : $lev;
                    } elseif ($exists) {
                        $alternatives[$collectionName] += $threshold;
                    }
                }
            }

            foreach ($collection as $item) {
                $lev = levenshtein($name, $item);

                if ($lev <= strlen($name) / 3 || false !== strpos($item, $name)) {
                    $alternatives[$item] = isset($alternatives[$item]) ? $alternatives[$item] - $lev : $lev;
                }
            }

            $alternatives = array_filter($alternatives, function ($lev) use ($threshold) { return $lev < 2 * $threshold; });
            ksort($alternatives, SORT_NATURAL | SORT_FLAG_CASE);

            return array_keys($alternatives);
        }

        private function splitStringByWidth($string, $width)
        {
            // str_split is not suitable for multi-byte characters, we should use preg_split to get char array properly.
            // additionally, array_slice() is not enough as some character has doubled width.
            // we need a function to split string not by character count but by string width
            if (false === $encoding = mb_detect_encoding($string, null, true))
                return str_split($string, $width);

            $utf8String = mb_convert_encoding($string, 'utf8', $encoding);
            $lines = array();
            $line = '';

            foreach (preg_split('//u', $utf8String) as $char) {
                // test if $char could be appended to current line
                if (mb_strwidth($line.$char, 'utf8') <= $width) {
                    $line .= $char;
                    continue;
                }

                // if not, push current line to array and make new line
                $lines[] = str_pad($line, $width);
                $line = $char;
            }

            $lines[] = count($lines) ? str_pad($line, $width) : $line;
            mb_convert_variables($encoding, 'utf8', $lines);

            return $lines;
        }

        /**
         * I have no clue.
         *
         * @param \Exception $e
         * @param OutputInterface $output
         */
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

        /**
         * Configures the input and output instances based on the user arguments and options.
         *
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

            if (true === $input->hasParameterOption(array('--no-interaction', '-n'), true))
                $input->setInteractive(false);

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
         * Gets the default input definition.
         *
         * @return InputDefinition An InputDefinition instance
         */
        protected function getDefaultInputDefinition()
        {
            return new InputDefinition(array(
                new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),
                new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message'),
                new InputOption('--quiet', '-q', InputOption::VALUE_NONE, 'Do not output any message'),
                new InputOption('--verbose', '-v|vv|vvv', InputOption::VALUE_NONE, 'Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug'),
                new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this application version'),
                new InputOption('--ansi', '', InputOption::VALUE_NONE, 'Force ANSI output'),
                new InputOption('--no-ansi', '', InputOption::VALUE_NONE, 'Disable ANSI output'),
                new InputOption('--no-interaction', '-n', InputOption::VALUE_NONE, 'Do not ask any interactive question'),
            ));
        }
    }