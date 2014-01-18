<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) IndigoPHP Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Command\Process;

use Indigo\Supervisor\Command\AbstractCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Restart Process Command
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class RestartProcessCommand extends AbstractCommand
{
    protected $arguments = array(
        array(
            'process',
            InputArgument::REQUIRED,
            'Process to restart'
        ),
    );

    protected function configure()
    {
        $this
            ->setName('process:restart')
            ->setDescription('Restart a process')
        ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = $input->getArgument('process');

        $output->writeln('<info>Restarting process: ' . $process . '</info>');
        $this->supervisor->stopProcess($process);
        $this->supervisor->startProcess($porcess, false);
    }
}
