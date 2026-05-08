<?php

namespace spec\Supervisor;

use fXmlRpc\ClientInterface;
use PhpSpec\ObjectBehavior;
use Supervisor\Process;
use Supervisor\Supervisor;
use Supervisor\TailLog;

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

    function it_tails_process_stdout_log(ClientInterface $client)
    {
        $client->call('supervisor.tailProcessStdoutLog', ['process_name', 0, 100])
            ->willReturn(['log content', 11, false]);

        $result = $this->tailProcessStdoutLog('process_name', 0, 100);

        $result->shouldHaveType(TailLog::class);
        $result->getBytes()->shouldReturn('log content');
        $result->getOffset()->shouldReturn(11);
        $result->isOverflow()->shouldReturn(false);
    }

    function it_tails_process_stderr_log(ClientInterface $client)
    {
        $client->call('supervisor.tailProcessStderrLog', ['process_name', 0, 100])
            ->willReturn(['error content', 13, true]);

        $result = $this->tailProcessStderrLog('process_name', 0, 100);

        $result->shouldHaveType(TailLog::class);
        $result->getBytes()->shouldReturn('error content');
        $result->getOffset()->shouldReturn(13);
        $result->isOverflow()->shouldReturn(true);
    }
}
