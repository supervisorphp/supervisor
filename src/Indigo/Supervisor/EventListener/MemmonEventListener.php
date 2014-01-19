<?php

namespace Indigo\Supervisor\EventListener;

use Indigo\Supervisor\Supervisor;
use Symfony\Component\Process\Process as SymfonyProcess;

class MemmonEventListener extends AbstractEventListener
{
    protected $supervisor;
    protected $program = array();
    protected $group = array();
    protected $any;
    protected $name = null;

    public function __construct(Supervisor $supervisor, array $program = array(), array $group = array(), $any, $name = null)
    {
        $this->supervisor = $supervisor;
        $this->program = $program;
        $this->group = $group;
        $this->any = $any;
        $this->name = $name;
    }

    protected function doListen(array $payload)
    {
        if (strpos($payload[0]['eventname'], 'TICK') == false) {
            return 0;
        }

        $processes = $this->supervisor->getAllProcess();

        foreach ($processes as $process) {
            $mem = $process->getMemUsage();

            if ($maxMem = $this->isListeningTo(array($process['name'], $pname), $this->programs) and $mem > $maxMem) {
                $this->restart($process, $mem);
            } elseif ($maxMem = $this->isListeningTo($process['group'], $this->groups) and $mem > $maxMem) {
                $this->restart($process, $mem);
            } elseif ($mem > intval($this->any)) {
                $this->restart($process, $mem);
            }
        }

        return 0;
    }

    protected function restart($process, $mem)
    {
        $process->restart();
    }

    protected function isListeningTo($what, $to = array())
    {
        if (is_array($what)) {
            foreach ($what as $w) {
                if (array_key_exists($w, $to)) {
                    return $to[$w];
                }
            }
        } else {
            if (array_key_exists($what, $to)) {
                return $to[$what];
            }
        }

        return false;
    }
}
