<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Command;

    use app\framework\Component\Console\Input\InputArgument;
    use app\framework\Component\Console\Input\InputOption;

    class HelpCommand extends Command
    {
        protected function configure()
        {
            $this->setName('help');
            $this->setDefinition([
                new InputArgument('command_name', InputArgument::OPTIONAL, 'The command name', 'help'),
                new InputOption('format', null, InputOption::VALUE_REQUIRED, 'The output format (txt, xml, json, or md)', 'txt'),
                new InputOption('raw', null, InputOption::VALUE_NONE, 'To output raw command help'),
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


    }