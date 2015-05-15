<?php

namespace spec\Supervisor;

use fXmlRpc\CallClient;
use Supervisor\Process;
use PhpSpec\ObjectBehavior;

class SupervisorSpec extends ObjectBehavior
{
    function let(CallClient $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Supervisor\Supervisor');
    }

    function it_checks_connection(CallClient $client)
    {
        $client->call('system.listMethods')->willReturn('response');

        $this->isConnected()->shouldReturn(true);

        $client->call('system.listMethods')->willThrow('Exception');

        $this->isConnected()->shouldReturn(false);
    }

    function it_checks_if_supervisor_is_running(CallClient $client)
    {
        $client->call('supervisor.getState', [])->willReturn(['statecode' => 1]);

        $this->isRunning()->shouldReturn(true);
    }

    function it_checks_supervisor_state(CallClient $client)
    {
        $client->call('supervisor.getState', [])->willReturn(['statecode' => 1]);

        $this->checkState(1)->shouldReturn(true);
    }

    function it_returns_all_processes(CallClient $client)
    {
        $client->call('supervisor.getAllProcessInfo', [])->willReturn([
            [
                'name' => 'process_name'
            ]
        ]);

        $processes = $this->getAllProcesses();

        $processes->shouldBeArray();
        $processes[0]->shouldHaveType('Supervisor\Process');
        $processes[0]->getName()->shouldReturn('process_name');
    }

    function it_returns_a_process_(CallClient $client)
    {
        $client->call('supervisor.getProcessInfo', ['process_name'])->willReturn(['name' => 'process_name']);

        $process = $this->getProcess('process_name');

        $process->shouldHaveType('Supervisor\Process');
        $process->getName()->shouldReturn('process_name');
    }
}
