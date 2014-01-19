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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Info Supervisor Command
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class InfoSupervisorCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('supervisor:info')
            ->setDescription('Get info about supervisor');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
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
            ->setRows($data);

        $output->writeln('<info>Supervisor status</info>');
        $table->render($output);
    }
}
