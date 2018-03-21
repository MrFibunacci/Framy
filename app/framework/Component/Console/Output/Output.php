<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Output;

    use app\framework\Component\Console\Output\Formatter\OutputFormatter;
    use app\framework\Component\Console\Output\Formatter\OutputFormatterInterface;

    /**
     * Base class for output classes.
     *
     * @package app\framework\Component\Console\Output
     */
    abstract class Output implements OutputInterface
    {
        private $verbosity;
        private $formatter;

        /**
        * @param int                           $verbosity The verbosity level (one of the VERBOSITY constants in OutputInterface)
        * @param bool                          $decorated Whether to decorate messages
        * @param OutputFormatterInterface|null $formatter Output formatter instance (null to use default OutputFormatter)
         */
        public function __construct(int $verbosity = self::VERBOSITY_NORMAL, bool $decorated = false, OutputFormatterInterface $formatter = null)
        {
            $this->verbosity = null === $verbosity ? self::VERBOSITY_NORMAL : $verbosity;
            $this->formatter = $formatter ?: new OutputFormatter();
            $this->formatter->setDecorated($decorated);
        }

        public function write($messages, $newline = false, $options = 0)
        {
            $messages = (array) $messages;

            $types = self::OUTPUT_NORMAL | self::OUTPUT_RAW | self::OUTPUT_PLAIN;
            $type = $types & $options ?: self::OUTPUT_NORMAL;

            $verbosities = self::VERBOSITY_QUIET | self::VERBOSITY_NORMAL | self::VERBOSITY_VERBOSE | self::VERBOSITY_VERY_VERBOSE | self::VERBOSITY_DEBUG;
            $verbosity = $verbosities & $options ?: self::VERBOSITY_NORMAL;

            if ($verbosity > $this->getVerbosity()) {
                return;
            }

            foreach ($messages as $message) {
                switch ($type) {
                    case OutputInterface::OUTPUT_NORMAL:
                        $message = $this->formatter->format($message);
                        break;
                    case OutputInterface::OUTPUT_RAW:
                        break;
                    case OutputInterface::OUTPUT_PLAIN:
                        $message = strip_tags($this->formatter->format($message));
                        break;
                }
                $this->doWrite($message, $newline);
            }
        }

        public function writeln($messages, $options = self::OUTPUT_NORMAL)
        {
            $this->write($messages, true, $options);
        }

        public function setVerbosity($level)
        {
            $this->verbosity = (int) $level;
        }

        public function getVerbosity()
        {
            return $this->verbosity;
        }

        public function isQuiet()
        {
            return self::VERBOSITY_QUIET === $this->verbosity;
        }

        public function isVerbose()
        {
            return self::VERBOSITY_VERBOSE <= $this->verbosity;
        }

        public function isVeryVerbose()
        {
            return self::VERBOSITY_VERY_VERBOSE <= $this->verbosity;
        }

        public function isDebug()
        {
            return self::VERBOSITY_DEBUG <= $this->verbosity;
        }

        /**
         * {@inheritdoc}
         */
        public function setFormatter(OutputFormatterInterface $formatter)
        {
            $this->formatter = $formatter;
        }

        /**
         * {@inheritdoc}
         */
        public function getFormatter()
        {
            return $this->formatter;
        }

        public function setDecorated($decorated)
        {
            $this->formatter->setDecorated($decorated);
        }

        public function isDecorated()
        {
            return $this->formatter->isDecorated();
        }

        /**
         * Writes a message to the output.
         *
         * @param string $message A message to write to the output
         * @param bool   $newline Whether to add a newline or not
         */
        abstract protected function doWrite($message, $newline);
    }