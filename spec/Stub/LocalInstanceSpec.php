<?php

namespace spec\Indigo\Supervisor\Stub;

use PhpSpec\ObjectBehavior;

class LocalInstanceSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\Stub\LocalInstance');
        $this->shouldUseTrait('Indigo\Supervisor\Connector\LocalInstance');
    }

    function it_checks_local_instance()
    {
        $this->isLocal()->shouldReturn(true);
    }

    public function getMatchers()
    {
        return [
            'useTrait' => function ($subject, $trait) {
                return class_uses($subject, $trait);
            }
        ];
    }
}
