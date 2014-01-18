<?php

namespace Indigo\Supervisor\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SupervisorCommand extends AbstractCommand
{
    protected $options = array();
    protected $arguments = array(
        array(
            'method',
            InputArgument::OPTIONAL,
            'What do you want to do?',
            'info',
        ),
    );

    protected function configure()
    {
        $this
            ->setName('supervisor')
            ->setDescription('Supervisor commands')
        ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('method')) {
            case 'info':
                $this->methodInfo($output);
                break;
            case 'shutdown':
                $this->methodShutdown($output);
                break;
            case 'restart':
                $this->methodRestart($output);
                break;
            case 'clear':
                $this->methodClear($output);
                break;
            default:
                $output->write($this->getHelp());
                break;
        }
    }

    private function methodInfo(OutputInterface $output)
    {
        $data = array();

        $state = $this->supervisor->getState();
        $data[] = array('State', $state['statename']);

        $data[] = array('PID', $this->supervisor->getPID());
        $data[] = array('Version', $this->supervisor->getSupervisorVersion());
        $data[] = array('API Version', $this->supervisor->getAPIVersion());

        $table = $this->getHelperSet()->get('table');
        $table
            ->setHeaders(array('Variable', 'Value'))
            ->setRows($data)
        ;

        $output->writeln('<info>Supervisor status</info>');
        $table->render($output);
    }

    private function methodShutdown(OutputInterface $output)
    {
        $output->writeln('<info>Shutting down supervisor</info>');
        $this->supervisor->shutdown();
    }

    private function methodRestart(OutputInterface $output)
    {
        $output->writeln('<info>Restarting supervisor</info>');
        $this->supervisor->restart();
    }

    private function methodClear(OutputInterface $output)
    {
        $output->writeln('<info>Clearing logs</info>');
        $this->supervisor->clearLog();
    }
}
