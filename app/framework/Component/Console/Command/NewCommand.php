<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Command;

    use app\framework\Component\Console\Input\InputArgument;
    use app\framework\Component\Console\Input\InputDefinition;
    use app\framework\Component\Console\Input\InputInterface;
    use app\framework\Component\Console\Output\ConsoleOutput;
    use app\framework\Component\Storage\File\File;
    use app\framework\Component\Storage\Storage;

    class NewCommand extends Command
    {
        private $commandTemplate =
<<<'EOD'
<?php
    
    namespace app\custom\Commands\§NAME§;
    
    use app\framework\Component\Console\Command\Command;
    use app\framework\Component\Console\Input\InputInterface;
    use app\framework\Component\Console\Output\ConsoleOutput;
    
    class §NAME§ extends Command
    {
        protected function configure()
        {
            $this->setName("§NAME§")
                ->setDescription("§DESCRIPTION§")
                ->setHelp("§HELPER§");
        }
        
        protected function execute(InputInterface $input, ConsoleOutput $output)
        {
            // here goes your functionality ... 
        }
    }
EOD;

        protected function configure()
        {
            $this->setName("new-command")
                ->setDefinition($this->createDefinition())
                ->setDescription("Create a new command")
                ->setHelp("Little helper to crate custom commands");
        }

        protected function execute(InputInterface $input, ConsoleOutput $output)
        {
            $newCommandName = $input->getArgument("name");

            $File = new File($newCommandName.".php", new Storage("commands"));
            fopen($File->getAbsolutePath(), "w");

            $tempDefaultCommand = str_replace("§NAME§", $newCommandName, $this->$commandTemplate);
            $tempDefaultCommand = str_replace("§DESCRIPTION§", $input->getArgument("description"), $tempDefaultCommand);
            $tempDefaultCommand = str_replace("§HELPER§", $input->getArgument("helper"), $tempDefaultCommand);

            file_put_contents($File->getAbsolutePath(), $tempDefaultCommand);
        }

        private function createDefinition()
        {
            return new InputDefinition([
                new InputArgument('name', InputArgument::REQUIRED, "The name of the command"),
                new InputArgument('description', InputArgument::OPTIONAL, "The Description of the command"),
                new InputArgument('helper', InputArgument::OPTIONAL, "The Description of the command"),
            ]);
        }
    }