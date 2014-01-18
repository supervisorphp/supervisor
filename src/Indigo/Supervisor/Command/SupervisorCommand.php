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
            'list',
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
            case 'state':
                $this->methodState($output);
                break;
            case 'stop':
                $this->methodStop($input, $output);
                break;
            case 'restart':
                $this->methodRestart($input, $output);
                break;
            case 'list':
                $this->methodList($output);
                break;
            default:
                $output->write($this->getHelp());
                break;
        }
    }

    private function methodState(OutputInterface $output)
    {
        $state = $this->supervisor->getState();
        $output->writeln('<info>Supervisor status: ' . $state['statename'] . '</info>');
    }

    private function methodStart(InputInterface $input, OutputInterface $output)
    {
        $process = $input->getArgument('process');

        $output->writeln('<info>Starting process: ' . $process . '</info>');
        $this->supervisor->startProcess($process, false);
    }

    private function methodStop(InputInterface $input, OutputInterface $output)
    {
        $process = $input->getArgument('process');

        $output->writeln('<info>Stopping process: ' . $process . '</info>');
        $this->supervisor->stopProcess($process, false);
    }

    private function methodRestart(InputInterface $input, OutputInterface $output)
    {
        $process = $input->getArgument('process');

        $output->writeln('<info>Restarting process: ' . $process . '</info>');
        $this->supervisor->stopProcess($process);
        $this->supervisor->startProcess($porcess, false);
    }
}
