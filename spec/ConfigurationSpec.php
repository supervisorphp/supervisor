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

    function it_should_allow_to_add_a_section_map()
    {
        $this->addSectionMap('test', 'stdClass');
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

    // function it_should_allow_to_be_casted_to_string(Section $supervisord)
    // {
    //     $supervisord->getName()->willReturn('supervisord');
    //     $supervisord->getProperties()->willReturn([
    //         'key1' => 'value',
    //         'key2' => true,
    //         'key3' => ['val1', 'val2', 'val3'],
    //     ]);

    //     $this->addSection($supervisord);

    //     $this->__toString()->shouldReturn("[supervisord]\nkey1 = value\nkey2 = true\nkey3 = val1,val2,val3\n\n");
    // }

    function it_should_allow_to_parse_an_ini_file()
    {
        $this->parseFile(__DIR__.'/../resources/example.conf');
    }

    function it_should_allow_to_parse_an_ini_string()
    {
        $ini = file_get_contents(__DIR__.'/../resources/example.conf');
        $this->parseString($ini);
    }

    function it_should_throw_an_exception_when_section_not_found()
    {
        $ini = "[invalid]\nkey=value";
        $this->shouldThrow('Indigo\Supervisor\Exception\UnknownSection')->duringParseString($ini);
    }
}
