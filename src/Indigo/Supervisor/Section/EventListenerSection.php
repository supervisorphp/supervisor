<?php

namespace Indigo\Supervisor\Section;

class EventListenerSection extends ProgramSection
{
    protected $optionalOptionsOverride = array(
        'buffer_size'    => 'integer',
        'events'         => 'array',
        'result_handler' => 'string',
    );

    public function __construct($name, array $options = array())
    {
        $this->optionalOptions = array_merge($this->optionalOptions, $this->optionalOptionsOverride);
        $this->resolveOptions($options);

        $this->name = 'eventlistener:' . trim($name);
    }
}
