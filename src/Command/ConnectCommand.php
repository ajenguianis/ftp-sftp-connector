<?php

declare(strict_types=1);

namespace AA\ServerConector\Command;

use AA\ServerConector\Connector\Connector;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConnectCommand extends ContainerAwareCommand
{
    use LockableTrait;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('connect:to-server')
            // the short description shown while running "php bin/console list"
            ->setDescription('clear symfony cache.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows yo to clear symfony psr cache...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $output->writeln('The command is already running in another process.');

            return 0;
        }
        $output->writeln(['START', '============', '']);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        $connector = new Connector();
        $connector->setProtocol('sftp');
        $connector->connect();

        $output->writeln(['END', '============', '']);
        $this->release();
    }
}