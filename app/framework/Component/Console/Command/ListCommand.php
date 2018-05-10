<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Command;

    use app\framework\Component\Console\Helper\DescriptorHelper;
    use app\framework\Component\Console\Input\InputArgument;
    use app\framework\Component\Console\Input\InputDefinition;
    use app\framework\Component\Console\Input\InputInterface;
    use app\framework\Component\Console\Input\InputOption;
    use app\framework\Component\Console\Output\ConsoleOutput;

    class ListCommand extends Command
    {
        /**
        * {@inheritdoc}
        */
        protected function configure()
        {
            $this
                ->setName('list')
                ->setDefinition($this->createDefinition())
                ->setDescription('Lists commands')
                ->setHelp("The <info>%command.name%</info> command lists all commands:
      <info>php %command.full_name%</info>
    You can also display the commands for a specific namespace:
      <info>php %command.full_name% test</info>");
        }

        /**
         * {@inheritdoc}
         */
        public function getNativeDefinition()
        {
            return $this->createDefinition();
        }

        /**
         * {@inheritdoc}
         */
        protected function execute(InputInterface $input, ConsoleOutput $output)
        {
            $helper = new DescriptorHelper();
            $helper->describe($output, $this->getKernel(), array(
                'format' => 'txt',
                'raw_text' => $input->getOption('raw'),
                'namespace' => $input->getArgument('namespace'),
            ));
        }

        /**
         * {@inheritdoc}
         */
        private function createDefinition()
        {
            return new InputDefinition(array(
                new InputArgument('namespace', InputArgument::OPTIONAL, 'The namespace name'),
                new InputOption('raw', null, InputOption::VALUE_NONE, 'To output raw command list'),
            ));
        }
    }