<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Indigo\Supervisor\Connector\UnixSocketConnector;
use Indigo\Supervisor\Connector\InetSocketConnector;
use Indigo\Supervisor\Supervisor;

/**
 * Abstract Symfony Command
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class AbstractCommand extends Command
{
    const VALUE_OPTIONAL_ARRAY = 12;

    protected $options = array();
    protected $arguments = array();
    protected $supervisor;

    private $defaultOptions = array(
        array(
            'unix',
            null,
            InputOption::VALUE_OPTIONAL,
            'Use Unix socket for connection',
            '/var/run/supervisor.sock'
        ),
        array(
            'host',
            null,
            InputOption::VALUE_OPTIONAL,
            'Use Internet socket for connection',
        ),
        array(
            'port',
            null,
            InputOption::VALUE_OPTIONAL,
            'Port used for connetion',
            9001,
        ),
        array(
            'timeout',
            null,
            InputOption::VALUE_OPTIONAL,
            'Connection timeout',
        ),
        array(
            'user',
            null,
            InputOption::VALUE_OPTIONAL,
            'Username'
        ),
        array(
            'pass',
            null,
            InputOption::VALUE_OPTIONAL,
            'Password'
        ),
    );

    /**
     * {@inheritdocs}
     */
    protected function configure()
    {
        $options = array_merge($this->options, $this->defaultOptions);

        foreach ($options as $option) {
            call_user_func_array(array($this, 'addOption'), $option);
        }

        foreach ($this->arguments as $argument) {
            call_user_func_array(array($this, 'addArgument'), $argument);
        }
    }

    /**
     * {@inheritdocs}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('host')) {
            $connector = new InetSocketConnector(
                $input->getOption('host'),
                $input->getOption('port'),
                $input->getOption('timeout')
            );
        } else {
            $connector = new UnixSocketConnector($input->getOption('unix'), $input->getOption('timeout'));
        }

        if ($input->getOption('user') and $input->getOption('pass')) {
            $connector->setCredentials($input->getOption('user'), $input->getOption('pass'));
        }

        $this->supervisor = new Supervisor($connector);
    }
}
