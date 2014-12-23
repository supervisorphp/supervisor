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

    function it_should_allow_to_set_a_property()
    {
        $this->setProperty('key', 'value2');

        $this->getProperty('key')->shouldReturn('value2');
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

    function it_should_allow_to_set_and_normalize_an_environmment_property()
    {
        $this->setProperty('environment', [
            'key1' => 'val1',
            'key2' => 'val2',
            'val3', // this should be ommitted
        ]);

        $this->getProperty('environment')->shouldReturn('KEY1="val1",KEY2="val2"');
    }
}
