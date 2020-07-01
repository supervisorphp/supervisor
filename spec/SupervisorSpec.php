<?php

namespace spec\Supervisor;

use fXmlRpc\ClientInterface;
use PhpSpec\ObjectBehavior;
use Supervisor\Process;
use Supervisor\Supervisor;

class SupervisorSpec extends ObjectBehavior
{
    function let(ClientInterface $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Supervisor::class);
    }

    function it_checks_connection(ClientInterface $client)
    {
        $client->call('system.listMethods', [])
            ->willReturn('response');

        $this->isConnected()->shouldReturn(true);

        $client->call('system.listMethods', [])
            ->willThrow(\RuntimeException::class);

        $this->isConnected()->shouldReturn(false);
    }

    function it_calls_a_method(ClientInterface $client)
    {
        $client->call('namespace.method', [])
            ->willReturn('response');

        $this->call('namespace', 'method')
            ->shouldReturn('response');
    }

    function it_checks_if_supervisor_is_running(ClientInterface $client)
    {
        $client->call('supervisor.getState', [])
            ->willReturn(['statecode' => 1]);

        $this->isRunning()->shouldReturn(true);
    }

    function it_checks_supervisor_state(ClientInterface $client)
    {
        $client->call('supervisor.getState', [])
            ->willReturn(['statecode' => 1]);

        $this->checkState(1)->shouldReturn(true);
    }

    function it_returns_all_processes(ClientInterface $client)
    {
        $client->call('supervisor.getAllProcessInfo', [])
            ->willReturn([
                [
                    'name' => 'process_name',
                ],
            ]);

        $processes = $this->getAllProcesses();

        $processes->shouldBeArray();
        $processes[0]->shouldHaveType(Process::class);
        $processes[0]->getName()->shouldReturn('process_name');
    }

    function it_returns_a_process_(ClientInterface $client)
    {
        $client->call('supervisor.getProcessInfo', ['process_name'])
            ->willReturn(['name' => 'process_name']);

        $process = $this->getProcess('process_name');

        $process->shouldHaveType(Process::class);
        $process->getName()->shouldReturn('process_name');
    }
}
