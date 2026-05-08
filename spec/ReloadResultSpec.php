<?php

namespace spec\Supervisor;

use PhpSpec\ObjectBehavior;
use Supervisor\ReloadResult;
use Supervisor\ReloadResultInterface;

class ReloadResultSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['added'], ['modified'], ['removed']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ReloadResult::class);
    }

    function it_implements_reload_result_interface()
    {
        $this->shouldImplement(ReloadResultInterface::class);
    }

    function it_returns_added_groups()
    {
        $this->getAdded()->shouldReturn(['added']);
    }

    function it_returns_modified_groups()
    {
        $this->getModified()->shouldReturn(['modified']);
    }

    function it_returns_removed_groups()
    {
        $this->getRemoved()->shouldReturn(['removed']);
    }

    function it_returns_all_affected_groups()
    {
        $this->getAffected()->shouldReturn(['added', 'modified', 'removed']);
    }

    function it_can_be_built_from_reload_config_response()
    {
        $this->beConstructedThrough('fromReloadConfig', [[
            [['added_group'], ['modified_group'], ['removed_group']]
        ]]);

        $this->getAdded()->shouldReturn(['added_group']);
        $this->getModified()->shouldReturn(['modified_group']);
        $this->getRemoved()->shouldReturn(['removed_group']);
    }

    function it_handles_empty_reload_config_response()
    {
        $this->beConstructedThrough('fromReloadConfig', [[]]);

        $this->getAdded()->shouldReturn([]);
        $this->getModified()->shouldReturn([]);
        $this->getRemoved()->shouldReturn([]);
    }
}
