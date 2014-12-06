<?php

namespace spec\Indigo\Supervisor;

use Indigo\Supervisor\Connector;
use Indigo\Supervisor\Process;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SupervisorSpec extends ObjectBehavior
{
    function let(Connector $connector)
    {
        $this->beConstructedWith($connector);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\Supervisor');
    }

    function it_should_allow_to_check_local_instance(Connector $connector)
    {
        $connector->isLocal()->willReturn(false);

        $this->isLocal()->shouldReturn(false);
    }

    function it_should_allow_to_call_a_method(Connector $connector)
    {
        $connector->call('namespace', 'method', [])->willReturn('response');

        $this->call('namespace', 'method')->shouldReturn('response');
    }

    function it_should_allow_to_check_running(Connector $connector)
    {
        $connector->call('supervisor', 'getState', [])->willReturn(['statecode' => 1]);

        $this->isRunning()->shouldReturn(true);
    }

    function it_should_allow_to_check_state(Connector $connector)
    {
        $connector->call('supervisor', 'getState', [])->willReturn(['statecode' => 1]);

        $this->checkState(1)->shouldReturn(true);
    }

    function it_should_allow_to_get_all_processes(Connector $connector)
    {
        $connector->call('supervisor', 'getAllProcessInfo', [])->willReturn([
            [
                'name' => 'process_name'
            ]
        ]);

        $processes = $this->getAllProcesses();

        $processes->shouldBeArray();
        $processes[0]->shouldHaveType('Indigo\Supervisor\Process');
        $processes[0]->getName()->shouldReturn('process_name');
    }

    function it_should_allow_to_get_a_process_(Connector $connector)
    {
        $connector->call('supervisor', 'getProcessInfo', ['process_name'])->willReturn(['name' => 'process_name']);

        $process = $this->getProcess('process_name');

        $process->shouldHaveType('Indigo\Supervisor\Process');
        $process->getName()->shouldReturn('process_name');
    }

    function it_should_allow_to_update_a_process_(Connector $connector, Process $process)
    {
        $process->getName()->willReturn('process_name');

        $connector->call('supervisor', 'getProcessInfo', ['process_name'])->willReturn([
            'name'  => 'process_name',
            'state' => 0,
        ]);

        $this->updateProcess($process);
    }
}
