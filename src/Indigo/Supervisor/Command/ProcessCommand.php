<?php

namespace Indigo\Supervisor\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessCommand extends AbstractCommand
{
    protected $options = array();
    protected $arguments = array(
        array(
            'method',
            InputArgument::OPTIONAL,
            'What do you want to do?',
            'list',
        ),
        array(
            'process',
            InputArgument::OPTIONAL,
            'Which process do you want to operate on?'
        ),
    );

    protected function configure()
    {
        $this
            ->setName('process')
            ->setDescription('Process commands')
        ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('method')) {
            case 'start':
                $this->methodStart($input, $output);
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

    private function methodList(OutputInterface $output)
    {
        $data = array();

        foreach ($this->supervisor->getAllProcessInfo() as $process) {
            $data[] = array(
                $process['statename'],
                $process['name'],
                $process['group'],
                $process['pid'],
            );
        }

        $table = $this->getHelperSet()->get('table');
        $table
            ->setHeaders(array('State', 'Name', 'Group', 'PID'))
            ->setRows($data)
        ;
        $table->render($output);
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
