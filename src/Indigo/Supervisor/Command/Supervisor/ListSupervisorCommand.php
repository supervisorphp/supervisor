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
 * List Supervisor Command
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class ListSupervisorCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('supervisor:list')
            ->setDescription('List processes')
        ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
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
}
