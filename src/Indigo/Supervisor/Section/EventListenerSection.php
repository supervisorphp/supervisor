<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventListenerSection extends ProgramSection
{
    protected $validOptionsOverride = array(
        'buffer_size'    => 'integer',
        'events'         => 'array',
        'result_handler' => 'string',
    );

    public function __construct($name, array $options = array())
    {
        $this->validOptions = array_merge($this->validOptions, $this->validOptionsOverride);
        $this->resolveOptions($options);

        $this->name = 'eventlistener:' . trim($name);
    }
}
