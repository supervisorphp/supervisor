<?php

namespace spec\Indigo\Supervisor\Stub;

use PhpSpec\ObjectBehavior;

class SectionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['key' => 'value']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\Stub\Section');
        $this->shouldHaveType('Indigo\Supervisor\Configuration\Section\Base');
        $this->shouldImplement('Indigo\Supervisor\Configuration\Section');
    }

    function it_should_allow_to_get_a_property()
    {
        $this->getProperty('key')->shouldReturn('value');
    }

    function it_should_allow_to_get_a_non_existent_property()
    {
        $this->getProperty('non_existent_key')->shouldReturn(null);
    }

    function it_should_allow_to_get_properties()
    {
        $this->getProperties()->shouldReturn(['key' => 'value']);
    }

    function it_should_allow_to_set_properties()
    {
        $this->setProperties(['key' => 'value2']);

        $this->getProperty('key')->shouldReturn('value2');
    }
}
