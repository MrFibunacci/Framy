<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Command;

    use app\framework\Component\Console\Input\InputArgument;
    use app\framework\Component\Console\Input\InputInterface;
    use app\framework\Component\Console\Input\InputOption;

    class HelpCommand extends Command
    {
        private $command;

        protected function configure()
        {
            $this->setName('help');
            $this->setDefinition([
                new InputArgument('command_name', InputArgument::OPTIONAL, 'The command name', 'help')
            ]);
            $this->setDescription('Displays help for a command');
            $this->setHelp(<<<'EOF'
The <info>%command.name%</info> command displays help for a given command:
  <info>php %command.full_name% list</info>
You can also output the help in other formats by using the <comment>--format</comment> option:
  <info>php %command.full_name% --format=xml list</info>
To display the list of available commands, please use the <info>list</info> command.
EOF
                );
        }

        public function setCommand(Command $command)
        {
            $this->command = $command;
        }

        /**
         * {@inheritdoc}
         */
        protected function execute(InputInterface $input)
        {
            if (null === $this->command) {
                $this->command = $this->getKernel()->find($input->getArgument('command_name'));
            }

            //TODO: give it to some sort of output parser to create a beautiful output
            echo $this->command->getHelp();

            $this->command = null;
        }


    }