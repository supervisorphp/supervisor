<?php

namespace spec\Indigo\Supervisor;

use PhpSpec\ObjectBehavior;

class ConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\Configuration');
    }

    function it_should_allow_to_parse_an_ini_file()
    {
        $this->parseFile(__DIR__.'/../resources/example.conf');
    }
}
