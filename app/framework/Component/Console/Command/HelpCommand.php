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
    use app\framework\Component\Console\Input\InputInterface;
    use app\framework\Component\Console\Input\InputOption;
    use app\framework\Component\Console\Output\ConsoleOutput;

    class HelpCommand extends Command
    {
        private $command;

        protected function configure()
        {
            $this->setName('help')
                ->setDefinition([
                    new InputArgument('command_name', InputArgument::OPTIONAL, 'The command name', 'help'),
                    new InputOption('raw', null, InputOption::VALUE_NONE, 'To output raw command help')
                ])
                ->setDescription('Displays help for a command')
                ->setHelp("The <info>%command.name%</info> command displays help for a given command:
  <info>php %command.full_name% list</info>
To display the list of available commands, please use the <info>list</info> command.");
        }

        public function setCommand(Command $command)
        {
            $this->command = $command;
        }

        /**
         * {@inheritdoc}
         */
        protected function execute(InputInterface $input, ConsoleOutput $output)
        {
            if (null === $this->command) {
                $this->command = $this->getKernel()->find($input->getArgument('command_name'));
            }

            $helper = new DescriptorHelper();
            $helper->describe($output, $this->command, [
                'format' => 'txt',
                'raw_text' => $input->getOption('raw')
            ]);

            $this->command = null;
        }
    }