<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Helper;

    use app\framework\Component\Console\Output\Descriptor\DescriptorInterface;
    use app\framework\Component\Console\Output\Descriptor\TextDescriptor;
    use app\framework\Component\Console\Output\OutputInterface;

    class DescriptorHelper extends Helper
    {
        /**
         * @var DescriptorInterface[]
         */
        private $descriptors = array();

        public function __construct()
        {
            $this->register('txt', new TextDescriptor());
        }

        /**
         * Describes an object if supported.
         *
         * Available options are:
         * * format: string, the output format name
         * * raw_text: boolean, sets output type as raw
         *
         * @param OutputInterface $output
         * @param object          $object
         * @param array           $options
         *
         * @throws \InvalidArgumentException when the given format is not supported
         */
        public function describe(OutputInterface $output, $object, array $options = array())
        {
            $options = array_merge(array(
                'raw_text' => false,
                'format' => 'txt',
            ), $options);

            if (!isset($this->descriptors[$options['format']]))
                throw new \InvalidArgumentException(sprintf('Unsupported format "%s".', $options['format']));

            $descriptor = $this->descriptors[$options['format']];
            $descriptor->describe($output, $object, $options);
        }

        /**
         * Registers a descriptor.
         *
         * @param string              $format
         * @param DescriptorInterface $descriptor
         *
         * @return $this
         */
        public function register($format, DescriptorInterface $descriptor)
        {
            $this->descriptors[$format] = $descriptor;

            return $this;
        }

        /**
         * {@inheritdoc}
         */
        public function getName()
        {
            return 'descriptor';
        }
    }