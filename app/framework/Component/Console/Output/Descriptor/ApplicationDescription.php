<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Output\Descriptor;

    use app\framework\Component\Console\Command\Command;
    use app\framework\Component\Console\Exception\CommandNotFoundException;
    use app\framework\Component\Console\Kernel;

    class ApplicationDescription
    {
        const GLOBAL_NAMESPACE = '_global';

        private $kernel;
        private $namespace;
        private $showHidden;

        /**
         * @var array
         */
        private $namespaces;

        /**
         * @var Command[]
         */
        private $commands;

        /**
         * @var Command[]
         */
        private $aliases;

        public function __construct(Kernel $kernel, string $namespace = null, bool $showHidden = false)
        {
            $this->kernel = $kernel;
            $this->namespace = $namespace;
            $this->showHidden = $showHidden;
        }

        /**
         * @return array
         */
        public function getNamespaces()
        {
            //if (null === $this->namespaces) {
            if ([] === $this->namespaces) {
                $this->inspectApplication();
            }

            return $this->namespaces;
        }

        /**
         * @return Command[]
         */
        public function getCommands()
        {
            if (null === $this->commands) {
                $this->inspectApplication();
            }

            return $this->commands;
        }

        /**
         * @param string $name
         *
         * @return Command
         *
         * @throws CommandNotFoundException
         */
        public function getCommand($name)
        {
            if (!isset($this->commands[$name]) && !isset($this->aliases[$name]))
                throw new CommandNotFoundException(sprintf('Command %s does not exist.', $name));

            return isset($this->commands[$name]) ? $this->commands[$name] : $this->aliases[$name];
        }

        private function inspectApplication()
        {
            $this->commands   = [];
            $this->namespaces = [];
            $all = $this->kernel->all($this->namespace ? $this->kernel->findNamespace($this->namespace) : null);
            //dd($this->kernel->findNamespace($this->namespace));

            foreach ($this->sortCommands($all) as $namespace => $commands) {
                $names = [];

                /** @var Command $command */
                foreach ($commands as $name => $command) {
                    if (!$command->getName() || (!$this->showHidden && $command->isHidden()))
                        continue;

                    if ($command->getName() === $name) {
                        $this->commands[$name] = $command;
                    } else {
                        $this->aliases[$name] = $command;
                    }

                    $names[] = $name;
                }

                $this->namespaces[$namespace] = ['id' => $namespace, 'commands' => $names];
            }
        }

        /**
         * Sort array of commands by array key.
         *
         * @param array $commands
         * @return array
         */
        private function sortCommands(array $commands): array
        {
            $namespacedCommands = [];
            $globalCommands     = [];

            foreach ($commands as $name => $command) {
                $key = $this->kernel->extractNamespace($name, 1);
                if (!$key) {
                    $globalCommands['_global'][$name] = $command;
                } else {
                    $namespacedCommands[$key][$name] = $command;
                }
            }

            ksort($namespacedCommands);
            $namespacedCommands = array_merge($globalCommands, $namespacedCommands);

            foreach ($namespacedCommands as &$commandsSet) {
                ksort($commandsSet);
            }

            // unset reference to keep scope clear
            unset($commandsSet);
            return $namespacedCommands;
        }
    }