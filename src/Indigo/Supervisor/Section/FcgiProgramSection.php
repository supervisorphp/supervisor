<?php

namespace Indigo\Supervisor\Section;

class FcgiProgramSection extends ProgramSection
{
    protected $requiredOptionsOverride = array(
        'socket'  => 'string',
    );

    protected $optionalOptionsOverride = array(
        'socket_owner' => 'string',
        'socket_mode'  => 'integer',
    );

    public function __construct($name, array $options = array())
    {
        $this->optionalOptions = array_merge($this->optionalOptions, $this->optionalOptionsOverride);
        $this->requiredOptions = array_merge($this->requiredOptions, $this->requiredOptionsOverride);
        $this->setOptions($options);

        $this->name = 'fcgi-program:' . trim($name);
    }
}
