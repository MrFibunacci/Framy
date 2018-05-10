<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\Console\Command;

    use app\framework\Component\Console\Input\InputInterface;
    use app\framework\Component\Console\Output\ConsoleOutput;
    use app\framework\Component\Config\Config;

    class FramyVersionCommand extends Command
    {
        protected function configure()
        {
            $this->setName("version")
                ->setDescription("Get the version of Framy");
        }

        protected function execute(InputInterface $input, ConsoleOutput $output)
        {
            $output->writeln(Config::getInstance()->get("version", "app"));
        }
    }