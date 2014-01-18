<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) IndigoPHP Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Command\Supervisor;

use Indigo\Supervisor\Command\AbstractCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Restart Supervisor Command
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class RestartSupervisorCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('supervisor:restart')
            ->setDescription('Restart supervisor')
        ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Restarting supervisor</info>');
        $this->supervisor->restart();
    }
}
