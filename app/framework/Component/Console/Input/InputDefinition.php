<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Input;

    /**
     * A InputDefinition represents a set of valid command line arguments and options.
     *
     * Usage:
     *
     *     $definition = new InputDefinition(array(
     *       new InputArgument('name', InputArgument::REQUIRED),
     *       new InputOption('foo', 'f', InputOption::VALUE_REQUIRED),
     *     ));
     *
     * @package app\framework\Component\Console\Input
     */
    class InputDefinition
    {
        private $arguments;
        private $hasAnArrayArgument = false;
        private $hasOptional;
        private $requiredCount;
        private $options;
        private $shortcuts;

        /**
         * @param array $definition An array of InputArgument and InputOption instance
         */
        public function __construct(array $definition = array())
        {
            $this->setDefinition($definition);
        }

        public function setDefinition(array $definition)
        {
            $arguments = array();
            $options   = array();
            foreach($definition as $item) {
                if($item instanceof InputOption) {
                    $options[] = $item;
                } else {
                    $arguments[] = $item;
                }
            }

            $this->setArguments($arguments);
            $this->setOptions($options);
        }

        public function setArguments($arguments = array())
        {
            $this->arguments = array();
            $this->requiredCount = 0;
            $this->hasOptional = false;
            $this->hasAnArrayArgument = false;
            $this->addArguments($arguments);
        }

        /**
         * Adds an array of InputArguments object.
         *
         * @param InputArgument[] $arguments An array of InputArguments objects
         */
        public function addArguments($arguments = array())
        {
            if($arguments !== null) {
                foreach($arguments as $argument) {
                    $this->addArgument($argument);
                }
            }
        }

        /**
         * @param InputArgument $argument
         * @throws \LogicException when incorrect argument is given.
         */
        public function addArgument(InputArgument $argument)
        {
            if(isset($this->arguments[$argument->getName()]))
                throw new \LogicException(sprintf('An argument with name "%s" already exists.', $argument->getName()));

            if($this->hasAnArrayArgument)
                throw new \LogicException('Cannot add an argument after an array argument.');

            if($argument->isRequired() && $this->hasOptional)
                throw new \LogicException('Cannot add a required argument after an optional one.');

            if($argument->isArray())
                $this->hasAnArrayArgument = true;

            if($argument->isRequired()) {
                ++$this->requiredCount;
            } else {
                $this->hasOptional = true;
            }

            $this->arguments[$argument->getName()] = $argument;
        }

        /**
         * Returns an InputArgument by name or by position.
         *
         * @param string|int $name The InputArgument name or position
         * @return InputArgument
         * @throws \InvalidArgumentException
         */
        public function getArgument($name)
        {
            if(!$this->hasArgument($name))
                throw new \InvalidArgumentException(sprintf('The "%s" argument does not exist.', $name));

            $arguments = is_int($name) ? array_values($this->arguments) : $this->arguments;

            return $arguments[$name];
        }

        /**
         * Return true if an InputArgument exist by name or position.
         *
         * @param string|int $name The InputArgument name or position
         * @return bool
         */
        public function hasArgument($name)
        {
            $arguments = is_int($name) ? array_values($this->arguments) : $this->arguments;

            return isset($arguments[$name]);
        }

        /**
         * Gets the array of InputArgument objects.
         *
         * @return InputArgument[]
         */
        public function getArguments()
        {
            return $this->arguments;
        }

        public function getArgumentCount()
        {
            return $this->hasAnArrayArgument ? PHP_INT_MAX : count($this->arguments);
        }

        /**
         * Returns the number of InputArguments.
         *
         * @return int
         */
        public function getArgumentRequiredCount()
        {
            return $this->requiredCount;
        }

        /**
         * Gets the default values.
         *
         * @return array An array of default values
         */
        public function getArgumentDefaults()
        {
            $values = array();
            foreach($this->arguments as $argument) {
                /**@var $argument InputArgument*/
                $values[$argument->getName()] = $argument->getDefault();
            }

            return $values;
        }

        /**
         * Sets the InputOption objects
         *
         * @param InputOption[] $options
         */
        public function setOptions($options = array())
        {
            $this->options = array();
            $this->addOptions($options);
        }

        public function getOption($name)
        {
            if(!$this->hasOption($name))
                throw new \InvalidArgumentException(sprintf('The "--%s" option does not exist.', $name));

            return $this->options[$name];
        }

        /**
         * adds an array of InputOption objects
         *
         * @param InputOption[] $options
         */
        public function addOptions($options = array())
        {
            foreach($options as $option) {
                $this->addOption($option);
            }
        }

        /**
         * @param InputOption[] $option
         * @throws \LogicException When option already exist
         */
        public function addOption($option)
        {
            /**@var $option InputOption*/
            if(isset($this->options[$option->getName()]) && !$option->equals($this->options[$option->getName()]))
                throw new \LogicException(sprintf('An option named "%s" already exists.', $option->getName()));

            $this->options[$option->getName()] = $option;
        }

        /**
         * Returns true if an InputOption exists by name
         *
         * This method can't be used to check if the user included the
         * option when executing the command (use getOption() instead).
         *
         * @param string $name The InputOption name
         * @return bool true if the InputOption exists, false otherwise
         */
        public function hasOption($name)
        {
            return isset($this->options[$name]);
        }

        /**
         * @return mixed
         */
        public function getOptions()
        {
            return $this->options;
        }

        /**
         * @return array An array of all default values
         */
        public function getOptionDefaults()
        {
            $values = array();
            foreach ($this->options as $option) {
                /**@var $option InputOption*/
                $values[$option->getName()] = $option->getDefault();
            }

            return $values;
        }

        /**
         * Returns true if an InputOption object exists by shortcut.
         *
         * @param string $name The InputOption shortcut
         *
         * @return bool true if the InputOption object exists, false otherwise
         */
        public function hasShortcut($name)
        {
            return isset($this->shortcuts[$name]);
        }

        /**
         * Gets an InputOption by shortcut.
         *
         * @param string $shortcut The Shortcut name
         *
         * @return InputOption An InputOption object
         */
        public function getOptionForShortcut($shortcut)
        {
            return $this->getOption($this->shortcutToName($shortcut));
        }

        /**
         * Returns the InputOption name given a shortcut.
         *
         * @param string $shortcut The shortcut
         *
         * @return string The InputOption name
         *
         * @throws \InvalidArgumentException When option given does not exist
         */
        private function shortcutToName($shortcut)
        {
            if (!isset($this->shortcuts[$shortcut]))
                throw new \InvalidArgumentException(sprintf('The "-%s" option does not exist.', $shortcut));

            return $this->shortcuts[$shortcut];
        }

        /**
         * Gets the synopsis.
         *
         * @param bool $short Whether to return the short version (with options folded) or not
         *
         * @return string The synopsis
         */
        public function getSynopsis($short = false)
        {
            $elements = array();
            if ($short && $this->getOptions()) {
                $elements[] = '[options]';
            } elseif (!$short) {
                foreach ($this->getOptions() as $option) {
                    /**@var $option InputOption*/
                    $value = '';
                    if ($option->acceptValue()) {
                        $value = sprintf(
                            ' %s%s%s',
                            $option->isValueOptional() ? '[' : '',
                            strtoupper($option->getName()),
                            $option->isValueOptional() ? ']' : ''
                        );
                    }
                    $shortcut = $option->getShortcut() ? sprintf('-%s|', $option->getShortcut()) : '';
                    $elements[] = sprintf('[%s--%s%s]', $shortcut, $option->getName(), $value);
                }
            }
            if (count($elements) && $this->getArguments())
                $elements[] = '[--]';

            foreach ($this->getArguments() as $argument) {
                $element = '<'.$argument->getName().'>';

                if (!$argument->isRequired()) {
                    $element = '['.$element.']';
                } elseif ($argument->isArray()) {
                    $element = $element.' ('.$element.')';
                }

                if ($argument->isArray())
                    $element .= '...';

                $elements[] = $element;
            }
            return implode(' ', $elements);
        }
    }