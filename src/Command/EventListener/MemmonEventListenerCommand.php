<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Command\EventListener;

use Indigo\Supervisor\Command\AbstractCommand;
use Indigo\Supervisor\EventListener\MemmonEventListener;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Memmon EventListener Command
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class MemmonEventListenerCommand extends AbstractCommand
{
    protected $options = array(
        array(
            'program',
            null,
            self::VALUE_OPTIONAL_ARRAY,
            'Program memory limits (program:limit)'
        ),
        array(
            'group',
            null,
            self::VALUE_OPTIONAL_ARRAY,
            'Group memory limits (group:limit)'
        ),
        array(
            'any',
            null,
            InputOption::VALUE_OPTIONAL,
            'Any memory limit'
        ),
        array(
            'uptime',
            null,
            InputOption::VALUE_OPTIONAL,
            'Minimum uptime before restart'
        ),
        array(
            'name',
            null,
            InputOption::VALUE_OPTIONAL,
            'Name of memmon instance'
        ),
    );
    /**
     * {@inheritdocs}
     */
    protected function configure()
    {
        $this
            ->setName('listener:memmon')
            ->setDescription('Start a Memmon EventListener');

        parent::configure();
    }

    /**
     * {@inheritdocs}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $program = $input->getOption('program');
        $group   = $input->getOption('group');
        $any     = $input->getOption('any');
        $uptime  = $input->getOption('uptime');
        $name    = $input->getOption('name');

        if (empty($program) and empty($group) and empty($any)) {
            throw new \Exception('You have to specify at least on program, group, or any option.');
        }

        $program = $this->parseOption($program);
        $group = $this->parseOption($group);

        $listener = new MemmonEventListener($this->supervisor, $program, $group, $any, $uptime, $name);

        $listener->listen();
    }

    private function parseOption(array $option)
    {
        return array_map(function ($item) {
            $program = explode('=', $item);

            if (count($program) !== 2) {
                throw new \InvalidArgumentException('Option should be in option=memory_limit form');
            }

            return $program;
        }, $option);
    }
}
