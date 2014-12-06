<?php

namespace spec\Indigo\Supervisor\Stub;

use PhpSpec\ObjectBehavior;

class ParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\Stub\Parser');
        $this->shouldHaveType('Indigo\Supervisor\Configuration\Parser\Base');
        $this->shouldImplement('Indigo\Supervisor\Configuration\Parser');
    }

    function it_should_allow_to_add_a_section_to_the_map()
    {
        $this->addSectionMap('test', 'stdClass');

        $this->findSection('test')->shouldReturn('stdClass');
    }

    function it_should_throw_an_exception_when_section_not_found()
    {
        $this->shouldThrow('Indigo\Supervisor\Exception\UnknownSection')->duringFindSection('invalid');
    }

    function it_should_allow_to_parse_an_array()
    {
        $this->addSectionMap('test', 'Indigo\Supervisor\Stub\Section');
        $sections = $this->parseArray(['test' => ['key' => 'value']]);

        $sections->shouldBeArray();
        $sections[0]->shouldHaveType('Indigo\Supervisor\Stub\Section');
        $sections[0]->getProperty('key')->shouldReturn('value');
    }

    function it_should_allow_to_parse_a_section()
    {
        $this->addSectionMap('test', 'Indigo\Supervisor\Stub\Section');
        $section = $this->parseSection('test', ['key' => 'value']);

        $section->shouldHaveType('Indigo\Supervisor\Stub\Section');
        $section->getProperty('key')->shouldReturn('value');
    }
}
