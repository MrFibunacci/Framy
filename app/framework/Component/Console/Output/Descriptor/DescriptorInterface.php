<?php
/**
 *
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Output\Descriptor;

    use app\framework\Component\Console\Output\OutputInterface;

    interface DescriptorInterface
    {
        /**
         * Describes an object if supported.
         *
         * @param OutputInterface $output
         * @param object          $object
         * @param array           $options
         */
        public function describe(OutputInterface $output, $object, array $options = array());
    }