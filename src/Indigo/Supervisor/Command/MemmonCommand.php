<?php

namespace Indigo\Supervisor\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Indigo\Supervisor\Supervisor;
use Indigo\Supervisor\Connector\ConnectorInterface;
use Indigo\Supervisor\Connector\UnixSocketConnector;
use Indigo\Supervisor\Connector\InetSocketConnector;
use Indigo\Supervisor\EventListener\MemmonEventListener;

class MemmonCommand extends Command
{
    protected $options = array(
        array(
            'unix',
            null,
            InputOption::VALUE_OPTIONAL,
            'If set, the task will use Unix socket for connection',
        ),
        array(
            'host',
            null,
            InputOption::VALUE_OPTIONAL,
            'If set, the task will use Internet socket for connection',
        ),
        array(
            'port',
            null,
            InputOption::VALUE_OPTIONAL,
            'If set, the task will use Internet socket for connection',
        ),
        array(
            'user',
            null,
            InputOption::VALUE_OPTIONAL,
            'Username',
        ),
        array(
            'pass',
            null,
            InputOption::VALUE_OPTIONAL,
            'Password',
        ),
        array(
            'program',
            null,
            12,
            'Memory limit/program',
        ),
        array(
            'group',
            null,
            12,
            'Memory limit/group',
        ),
        array(
            'any',
            null,
            InputOption::VALUE_OPTIONAL,
            'Memory limit for all',
        ),
    );

    protected function configure()
    {
        $this
            ->setName('memmon')
            ->setDescription('Memmon listener')
        ;

        foreach ($this->options as $option) {
            list($name, $null, $bit, $desc) = $option;
            $this->addOption($name, $null, $bit, $desc);
        }
    }

    private function init(InputInterface $input)
    {
        if ($input->getOption('unix')) {
            $connector = new UnixSocketConnector($input->getOption('unix'));
        } elseif ($input->getOption('host')) {
            $port = $input->getOption('port') ?: 9001;
            $connector = new InetSocketConnector($input->getOption('host'), $port);
        } else {
            throw new \Exception('No connection data found');
        }

        if ($input->getOption('user') and $input->getOption('pass')) {
            $connector->setCredentials($input->getOption('user'), $input->getOption('pass'));
        }

        return new Supervisor($connector);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $supervisor = $this->init($input);

        $program = array();
        if ($input->hasOption('program')) {
            foreach ($input->getOption('program') as $p) {
                $p = explode(':', $p);
                $program[$p[0]] = $p[1];
            }
        }

        $group = array();
        if ($input->hasOption('group')) {
            foreach ($input->getOption('group') as $p) {
                $p = explode(':', $p);
                $group[$p[0]] = $p[1];
            }
        }

        $any = $input->hasOption('any') ? $input->getOption('any') : 0;

        $listener = new MemmonEventListener($supervisor, $program, $group, $any);

        $listener->listen();
    }
}