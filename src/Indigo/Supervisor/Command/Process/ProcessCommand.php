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
 * Abstract Process Command
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class ProcessCommand extends AbstractCommand
{
    protected $arguments = array(
        array(
            'process',
            InputArgument::REQUIRED,
            'Process to stop'
        ),
    );
}
