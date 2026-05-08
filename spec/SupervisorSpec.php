<?php

namespace spec\Supervisor;

use fXmlRpc\ClientInterface;
use PhpSpec\ObjectBehavior;
use Supervisor\Process;
use Supervisor\ReloadResult;
use Supervisor\ServiceStates;
use Supervisor\StateInfo;
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
            ->willReturn(['statecode' => 1, 'statename' => 'RUNNING']);

        $this->isRunning()->shouldReturn(true);
    }

    function it_checks_supervisor_state(ClientInterface $client)
    {
        $client->call('supervisor.getState', [])
            ->willReturn(['statecode' => 1, 'statename' => 'RUNNING']);

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

    function it_returns_state(ClientInterface $client)
    {
        $client->call('supervisor.getState', [])
            ->willReturn(['statecode' => 1, 'statename' => 'RUNNING']);

        $result = $this->getState();

        $result->shouldHaveType(StateInfo::class);
        $result->getStateCode()->shouldReturn(ServiceStates::Running);
        $result->getStateName()->shouldReturn('RUNNING');
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

    function it_removes_groups_on_reload(ClientInterface $client)
    {
        $client->call('supervisor.reloadConfig', [])->willReturn([[[], [], ['old_group']]]);
        $client->call('supervisor.stopProcessGroup', ['old_group', true])->willReturn([]);
        $client->call('supervisor.removeProcessGroup', ['old_group'])->willReturn(true);

        $result = $this->reloadAndApplyConfig();

        $result->shouldHaveType(ReloadResult::class);
        $result->getRemoved()->shouldReturn(['old_group']);
    }

    function it_restarts_modified_groups_on_reload(ClientInterface $client)
    {
        $client->call('supervisor.reloadConfig', [])->willReturn([[[], ['changed_group'], []]]);
        $client->call('supervisor.stopProcessGroup', ['changed_group', true])->willReturn([]);
        $client->call('supervisor.removeProcessGroup', ['changed_group'])->willReturn(true);
        $client->call('supervisor.addProcessGroup', ['changed_group'])->willReturn(true);
        $client->call('supervisor.startProcessGroup', ['changed_group'])->willReturn([]);

        $result = $this->reloadAndApplyConfig();

        $result->shouldHaveType(ReloadResult::class);
        $result->getModified()->shouldReturn(['changed_group']);
    }

    function it_does_not_stop_modified_groups_when_flag_is_false(ClientInterface $client)
    {
        $client->call('supervisor.reloadConfig', [])->willReturn([[[], ['changed_group'], []]]);
        $client->call('supervisor.stopProcessGroup', ['changed_group', true])->shouldNotBeCalled();
        $client->call('supervisor.removeProcessGroup', ['changed_group'])->shouldNotBeCalled();
        $client->call('supervisor.addProcessGroup', ['changed_group'])->willReturn(true);
        $client->call('supervisor.startProcessGroup', ['changed_group'])->willReturn([]);

        $this->reloadAndApplyConfig(true, false, true);
    }

    function it_starts_added_groups_on_reload(ClientInterface $client)
    {
        $client->call('supervisor.reloadConfig', [])->willReturn([[['new_group'], [], []]]);
        $client->call('supervisor.addProcessGroup', ['new_group'])->willReturn(true);
        $client->call('supervisor.startProcessGroup', ['new_group'])->willReturn([]);

        $result = $this->reloadAndApplyConfig();

        $result->shouldHaveType(ReloadResult::class);
        $result->getAdded()->shouldReturn(['new_group']);
    }

    function it_does_not_start_new_processes_when_flag_is_false(ClientInterface $client)
    {
        $client->call('supervisor.reloadConfig', [])->willReturn([[['new_group'], [], []]]);
        $client->call('supervisor.addProcessGroup', ['new_group'])->willReturn(true);
        $client->call('supervisor.startProcessGroup', ['new_group'])->shouldNotBeCalled();

        $this->reloadAndApplyConfig(true, true, false);
    }
}
