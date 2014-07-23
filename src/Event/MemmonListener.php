<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Event;

use Indigo\Supervisor\Supervisor;
use Indigo\Supervisor\Process;
use Psr\Log\NullLogger;
use Exception;

/**
 * Memmon EventListener
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class MemmonListener extends AbstractListener
{
    /**
     * Supervisor instance
     *
     * @var Supervisor
     */
    protected $supervisor;

    /**
     * Array of program=>limit pairs
     *
     * @var array
     */
    protected $program = array();

    /**
     * Array of group=>limit pairs
     *
     * @var array
     */
    protected $group = array();

    /**
     * Any memory limit
     *
     * @var integer
     */
    protected $any;

    /**
     * Minimum uptime before restart
     *
     * @var integer
     */
    protected $uptime;

    /**
     * Name of memmon instance
     * Only has a meaning if you use logging
     *
     * @var string
     */
    protected $name = null;

    /**
     * Creates a MemmonListener
     *
     * @param Supervisor $supervisor Supervisor instance
     * @param array      $program    Limit of specified programs
     * @param array      $group      Limit of specified groups
     * @param integer    $any        Limit of any programs or groups
     * @param integer    $uptime     Minimum uptime before restart
     * @param string     $name       Listener name
     */
    public function __construct(
        Supervisor $supervisor,
        array $program = array(),
        array $group = array(),
        $any = 0,
        $uptime = 60,
        $name = null
    ) {
        $this->supervisor = $supervisor;
        $this->program    = $program;
        $this->group      = $group;
        $this->any        = intval($any);
        $this->uptime     = $uptime;
        $this->name       = $name;
        $this->logger     = new NullLogger;
    }

    /**
     * {@inheritdoc}
     */
    protected function doListen(EventInterface $event)
    {
        if (strpos($event->getHeader('eventname', ''), 'TICK') === false) {
            return 0;
        }

        $processes = $this->supervisor->getAllProcesses();

        foreach ($processes as $process) {
            if ($this->checkProcess($process)) {
                $this->handleProcess($process);
            }
        }

        return 0;
    }

    /**
     * Handle process
     *
     * @param Process $process
     */
    protected function handleProcess(Process $process)
    {
        $mem = $process->getMemUsage();
        $max = $this->getMaxMemory($process);

        if ($max > 0 and $mem > $max) {
            $this->restart($process, $mem);
        }
    }

    /**
     * Restarts a process
     *
     * @param Process $process
     * @param integer $mem     Current memory usage
     *
     * @return boolean Whether restart is successful
     */
    protected function restart(Process $process, $mem)
    {
        try {
            $result = $process->restart();
        } catch (Exception $e) {
            $result = false;
        }

        $message = $result ? '[Success]' : '[Failure]';
        $message .= '(' . ($this->name ? $this->name . '/' : '') . $process['name'] . ') ';
        $context = array(
            'subject' => $message,
            'payload' => $process->getPayload(),
        );

        $message .= 'Process restart at ' . $mem . ' bytes';

        $this->logger->info($message, $context);

        return $result;
    }

    /**
     * Checks whether listener should care about this process
     *
     * @param Process $process
     *
     * @return boolean
     */
    protected function checkProcess(Process $process)
    {
        $return = $process->isRunning();

        if ($return) {
            $return = $process['now'] - $process['start'] > $this->uptime;
        }

        return $return;
    }

    /**
     * Returns the maximum memory allowed for this process
     *
     * @param Process $process
     *
     * @return integer
     */
    protected function getMaxMemory(Process $process)
    {
        $pname = $process['group'] . ':' . $process['name'];

        $mem = array(
            $this->hasProgram($process['name']),
            $this->hasProgram($pname),
            $this->hasGroup($process['group']),
            $this->any,
        );

        return abs(max($mem));
    }

    /**
     * Checks whether listener has limit for the given program and return it
     *
     * @param string $program
     *
     * @return integer
     */
    protected function hasProgram($program)
    {
        return array_key_exists($program, $this->program) ? $this->program[$program] : 0;
    }

    /**
     * Checks whether listener has limit for the given group and return it
     *
     * @param string $group
     *
     * @return integer
     */
    protected function hasGroup($group)
    {
        return array_key_exists($group, $this->group) ? $this->group[$group] : 0;
    }
}
