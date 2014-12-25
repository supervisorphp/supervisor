<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Event\Handler;

use Indigo\Supervisor\Event\Handler;
use Indigo\Supervisor\Event\Notification;
use Indigo\Supervisor\Supervisor;
use Indigo\Supervisor\Process;
use Exception;

/**
 * Implements memmon listener logic
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @codeCoverageIgnore
 */
class Memmon implements Handler
{
    /**
     * @var Supervisor
     */
    protected $supervisor;

    /**
     * Array of program => limit pairs
     *
     * @var array
     */
    protected $program = [];

    /**
     * Array of group => limit pairs
     *
     * @var array
     */
    protected $group = [];

    /**
     * Any memory limit
     *
     * @var integer
     */
    protected $any;

    /**
     * Minimum uptime before restart
     *
     * Prevents restart loops
     *
     * @var integer
     */
    protected $uptime;

    /**
     * @param Supervisor $supervisor
     * @param []         $program
     * @param []         $group
     * @param integer    $any
     * @param integer    $uptime
     */
    public function __construct(
        Supervisor $supervisor,
        array $program = [],
        array $group = [],
        $any = 0,
        $uptime = 60
    ) {
        $this->supervisor = $supervisor;
        $this->program    = $program;
        $this->group      = $group;
        $this->any        = (int) $any;
        $this->uptime     = $uptime;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Notification $notification)
    {
        $processes = $this->supervisor->getAllProcesses();

        foreach ($processes as $process) {
            if ($this->checkProcess($process)) {
                $this->handleProcess($process);
            }
        }
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
        return $process->isRunning() and $process['now'] - $process['start'] > $this->uptime;
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
     * Returns the maximum memory allowed for this process
     *
     * @param Process $process
     *
     * @return integer
     */
    protected function getMaxMemory(Process $process)
    {
        $pname = $process['group'] . ':' . $process['name'];

        $mem = [
            $this->hasProgram($process['name']),
            $this->hasProgram($pname),
            $this->hasGroup($process['group']),
            $this->any,
        ];

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
        return array_key_exists($program, $this->program) ? (int) $this->program[$program] : 0;
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
        return array_key_exists($group, $this->group) ? (int) $this->group[$group] : 0;
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
            return $process->restart();
        } catch (Exception $e) {
        }

        return false;
    }
}
