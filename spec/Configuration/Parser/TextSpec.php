<?php

namespace spec\Indigo\Supervisor\Configuration\Parser;

use Indigo\Supervisor\Configuration;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TextSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith("[supervisord]\nidentifier = supervisor");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\Configuration\Parser\Text');
        $this->shouldHaveType('Indigo\Supervisor\Configuration\Parser\Base');
        $this->shouldImplement('Indigo\Supervisor\Configuration\Parser');
    }

    function it_should_throw_an_exception_when_invalid_file_given()
    {
        $this->shouldThrow('InvalidArgumentException')->during('__construct', [true]);
    }

    function it_should_allow_to_parse(Configuration $configuration)
    {
        $configuration->addSections(Argument::type('array'))->shouldBeCalled();

        $this->parse($configuration);
    }

    function it_should_allow_to_parse_to_a_new_configuration()
    {
        $configuration = $this->parse();

        $configuration->shouldHaveType('Indigo\Supervisor\Configuration');
    }

    function it_should_throw_an_exception_when_parsing_failed()
    {
        $this->beConstructedWith('?{}|&~![()^"');

        $this->shouldThrow('Indigo\Supervisor\Exception\ParsingFailed')->duringParse();
    }
}
