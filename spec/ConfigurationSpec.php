<?php

namespace spec\Indigo\Supervisor;

use Indigo\Supervisor\Configuration\Section;
use PhpSpec\ObjectBehavior;

class ConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\Configuration');
    }

    function it_should_allow_to_get_a_section(Section $supervisord)
    {
        $supervisord->getName()->willReturn('supervisord');

        $this->getSection('supervisord')->shouldReturn(null);

        $this->addSection($supervisord);

        $this->getSection('supervisord')->shouldReturn($supervisord);
    }

    function it_should_allow_to_check_a_section(Section $supervisord)
    {
        $supervisord->getName()->willReturn('supervisord');

        $this->hasSection('supervisord')->shouldReturn(false);

        $this->addSection($supervisord);

        $this->hasSection('supervisord')->shouldReturn(true);
    }

    function it_should_allow_to_remove_a_section(Section $supervisord)
    {
        $supervisord->getName()->willReturn('supervisord');

        $this->addSection($supervisord);

        $this->hasSection('supervisord')->shouldReturn(true);

        $this->removeSection('supervisord')->shouldReturn(true);

        $this->hasSection('supervisord')->shouldReturn(false);
    }

    function it_should_allow_to_get_sections(Section $supervisord)
    {
        $supervisord->getName()->willReturn('supervisord');

        $this->getSections()->shouldReturn([]);

        $this->addSection($supervisord);

        $this->getSections()->shouldReturn(['supervisord' => $supervisord]);
    }

    function it_should_allow_to_add_sections(Section $supervisord)
    {
        $supervisord->getName()->willReturn('supervisord');

        $this->addSections([$supervisord]);

        $this->getSections()->shouldReturn(['supervisord' => $supervisord]);
    }

    function it_should_allow_to_reset(Section $supervisord)
    {
        $supervisord->getName()->willReturn('supervisord');

        $this->addSection($supervisord);

        $this->reset();

        $this->getSections()->shouldReturn([]);
    }
}
