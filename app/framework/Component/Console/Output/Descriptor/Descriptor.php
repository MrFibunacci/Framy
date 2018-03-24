<?php
/**
 *
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */
    namespace app\framework\Component\Console\Output\Descriptor;

    use app\framework\Component\Console\Command\Command;
    use app\framework\Component\Console\Input\InputArgument;
    use app\framework\Component\Console\Input\InputDefinition;
    use app\framework\Component\Console\Input\InputOption;
    use app\framework\Component\Console\Kernel;
    use app\framework\Component\Console\Output\OutputInterface;

    abstract class Descriptor implements DescriptorInterface
    {
        /**
         * @var OutputInterface
         */
        protected $output;

        /**
         * {@inheritdoc}
         */
        public function describe(OutputInterface $output, $object, array $options = array())
        {
            $this->output = $output;
            switch (true) {
                case $object instanceof InputArgument:
                    $this->describeInputArgument($object, $options);
                    break;
                case $object instanceof InputOption:
                    $this->describeInputOption($object, $options);
                    break;
                case $object instanceof InputDefinition:
                    $this->describeInputDefinition($object, $options);
                    break;
                case $object instanceof Command:
                    $this->describeCommand($object, $options);
                    break;
                case $object instanceof Kernel:
                    $this->describeApplication($object, $options);
                    break;
                default:
                    throw new \InvalidArgumentException(sprintf('Object of type "%s" is not describable.', get_class($object)));
            }
        }

        /**
         * Writes content to output.
         *
         * @param string $content
         * @param bool   $decorated
         */
        protected function write($content, $decorated = false)
        {
            $this->output->write($content, false, $decorated ? OutputInterface::OUTPUT_NORMAL : OutputInterface::OUTPUT_RAW);
        }

        /**
         * Describes an InputArgument instance.
         *
         * @return string|mixed
         */
        abstract protected function describeInputArgument(InputArgument $argument, array $options = array());

        /**
         * Describes an InputOption instance.
         *
         * @return string|mixed
         */
        abstract protected function describeInputOption(InputOption $option, array $options = array());

        /**
         * Describes an InputDefinition instance.
         *
         * @return string|mixed
         */
        abstract protected function describeInputDefinition(InputDefinition $definition, array $options = array());

        /**
         * Describes a Command instance.
         *
         * @return string|mixed
         */
        abstract protected function describeCommand(Command $command, array $options = array());

        /**
         * Describes an Application instance.
         *
         * @return string|mixed
         */
        abstract protected function describeApplication(Kernel $kernel, array $options = array());
    }