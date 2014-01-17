<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) IndigoPHP Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Section;

/**
 * Event Listener Section
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
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
