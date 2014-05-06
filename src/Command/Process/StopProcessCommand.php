<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Command\Process;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Stop Process Command
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class StopProcessCommand extends ProcessCommand
{
    protected function configure()
    {
        $this
            ->setName('process:stop')
            ->setDescription('Stop a process');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = $input->getArgument('process');

        $output->writeln('<info>Stopping process: ' . $process . '</info>');
        $this->supervisor->stopProcess($process, false);
    }
}
