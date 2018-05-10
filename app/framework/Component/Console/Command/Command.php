<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Command;

    use app\framework\Component\Console\Input\InputDefinition;
    use app\framework\Component\Console\Input\InputInterface;
    use app\framework\Component\Console\Output\ConsoleOutput;

    /**
     * Base command class.
     *
     * @package app\framework\Component\Console
     */
    class Command
    {
        private $name;
        private $kernel;
        private $signature;
        private $description;
        private $definition;
        private $ignoreValidationErrors;
        private $help;
        private $code;
        private $hidden = false;
        private $aliases;

        /**
         * Command constructor.
         *
         * @param $name
         */
        public function __construct(string $name = null)
        {
            if(!is_null($name))
                $this->setName($name);

            $this->definition = new InputDefinition();

            $this->configure();
        }

        /**
         * Configures the current command.
         */
        protected function configure()
        {
        }

        /**
         * Checks whether the command is enabled or not in the current environment.
         *
         * Override this to check for x or y and return false if the command can not
         * run properly under the current conditions.
         *
         * @return bool
         */
        public function isEnabled()
        {
            return true;
        }

        /**
         * Interacts with the user.
         *
         * This method is executed before the InputDefinition is validated.
         * This means that this is the only place where the command can
         * interactively ask for values of missing required arguments.
         *
         * @param InputInterface $input
         */
        protected function interact(InputInterface $input)
        {
        }

        protected function execute(InputInterface $input, ConsoleOutput $output)
        {
            throw new \LogicException('You must override the execute() method in the concrete command class.');
        }

        /**
         * @return mixed
         */
        public function getKernel()
        {
            return $this->kernel;
        }

        /**
         * @param mixed $kernel
         */
        public function setKernel($kernel)
        {
            $this->kernel = $kernel;
        }

        /**
         * Runs the command.
         *
         * The code to execute is either defined directly with the
         * setCode() method or by overriding the execute() method in a sub-class.
         *
         * @param InputInterface $input
         * @param ConsoleOutput $output
         * @return int
         * @throws \Exception
         */
        public function run(InputInterface $input, ConsoleOutput $output)
        {
            // bind the input against the command specific arguments/options
            try {
                $input->bind($this->definition);
            } catch (\Exception $e) {
                if (!$this->ignoreValidationErrors) {
                    throw $e;
                }
            }

            if ($input->isInteractive())
                $this->interact($input);

            $input->validate();

            if ($this->code) {
                $statusCode = call_user_func($this->code, $input);
            } else {
                $statusCode = $this->execute($input, $output);
            }

            return is_numeric($statusCode) ? (int) $statusCode : 0;
        }

        /**
         * Returns the processed help for the command replacing the %command.name% and
         * %command.full_name% patterns with the real values dynamically.
         *
         * @return string The processed help for the command
         */
        public function getProcessedHelp()
        {
            $name = $this->name;

            $placeholders = array(
                '%command.name%',
                '%command.full_name%',
            );

            $replacements = array(
                $name,
                $_SERVER['PHP_SELF'].' '.$name,
            );

            return str_replace($placeholders, $replacements, $this->getHelp() ?: $this->getDescription());
        }

        /**
         * Sets the code to execute when running this command.
         *
         * If this method is used, it ov
         *
         * @param callable $code A callable
         * @return $this
         * @throws \ReflectionException
         * @see execute()
         */
        public function setCode(callable $code)
        {
            if($code instanceof \Closure) {
                $r = new \ReflectionFunction($code);
                if(null === $r->getClosureThis()) {
                    $code = \Closure::bind($code, $this);
                }
            }

            $this->code = $code;

            return $this;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param string $name
         * @return $this
         */
        public function setName($name)
        {
            $this->validateName($name);

            $this->name = $name;

            return $this;
        }

        /**
         * @return string
         */
        public function getSignature()
        {
            return $this->signature;
        }

        /**
         * @param string $signature
         */
        public function setSignature($signature)
        {
            $this->signature = $signature;
        }


        /**
         * @param bool $hidden Whether or not the command should be hidden from the list of commands
         *
         * @return Command The current instance
         */
        public function setHidden($hidden)
        {
            $this->hidden = (bool) $hidden;

            return $this;
        }

        /**
         * @return bool whether the command should be publicly shown or not
         */
        public function isHidden()
        {
            return $this->hidden;
        }

        /**
         * @return string
         */
        public function getDescription()
        {
            return $this->description;
        }

        /**
         * @param string $description
         * @return $this
         */
        public function setDescription($description)
        {
            $this->description = $description;

            return $this;
        }

        /**
         * Gets the InputDefinition to be used to create representations of this Command.
         *
         * Can be overridden to provide the original command representation when it would otherwise
         * be changed by merging with the application InputDefinition.
         *
         * This method is not part of public API and should not be used directly.
         *
         * @return InputDefinition An InputDefinition instance
         */
        public function getNativeDefinition()
        {
            return $this->getDefinition();
        }

        /**
         * @return string
         */
        public function getHelp()
        {
            return $this->help;
        }

        /**
         * @param string $help
         */
        public function setHelp($help)
        {
            $this->help = $help;
        }

        /**
         * Sets an array of argument and option instances.
         *
         * @param array|InputDefinition $definition An array of argument and option instances or a definition instance
         *
         * @return $this
         */
        public function setDefinition($definition)
        {
            if ($definition instanceof InputDefinition) {
                $this->definition = $definition;
            } else {
                $this->definition->setDefinition($definition);
            }

            return $this;
        }

        /**
         * Gets the InputDefinition attached to this Command.
         *
         * @return InputDefinition An InputDefinition instance
         */
        public function getDefinition()
        {
            return $this->definition;
        }

        /**
         * Returns the aliases for the command.
         *
         * @return array An array of aliases for the command
         */
        public function getAliases()
        {
            return $this->aliases;
        }

        /**
         * Validates a command name
         *
         * It must be non-empty and parts can optionally be separated by ":".
         *
         * @param string $name
         * @throws \InvalidArgumentException When the name is invalid
         */
        private function validateName($name)
        {
            if(!preg_match('/^[^\:]++(\:[^\:]++)*$/', $name))
                throw new \InvalidArgumentException(sprintf('Command name "%s" is invalid.', $name));
        }
    }