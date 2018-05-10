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

    class NewController extends Command
    {
        private $controllerTemplate =
            <<<'EOD'
<?php
    
    namespace app\custom\Http\Controller\§NAME§;
    
    class §NAME§
    {
       // stuff
    }
EOD;
        protected function configure()
        {
            $this->setName("new-controller")
                ->setDescription("Adds an new Controller")
                ->setDefinition(new InputDefinition([
                    new InputArgument("name", InputArgument::REQUIRED, "The name of the Controller")
                ]))
                ->setHelp("To add an controller directly where it belongs");
        }

        protected function execute(InputInterface $input, ConsoleOutput $output)
        {
            $newControllerName = $input->getArgument("name");

            $File = new File($newControllerName.".php", new Storage("controller"));
            fopen($File->getAbsolutePath(), "w");

            $tempDefaultCommand = str_replace("§NAME§", $newControllerName, $this->controllerTemplate);

            file_put_contents($File->getAbsolutePath(), $tempDefaultCommand);
        }
    }