<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Section;

/**
 * Event Listener section
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class EventListenerSection extends ProgramSection
{
    /**
     * {@inheritdocs}
     */
    protected $sectionName = 'eventlistener';

    /**
     * {@inheritdocs}
     */
    protected $optionalOptionsOverride = array(
        'buffer_size'    => 'integer',
        'events'         => 'array',
        'result_handler' => 'string',
    );

    /**
     * Creates an EventListener section
     *
     * @param string $name
     * @param array  $options
     *
     * @codeCoverageIgnore
     */
    public function __construct($name, array $options = array())
    {
        $this->optionalOptions = array_merge($this->optionalOptions, $this->optionalOptionsOverride);

        parent::__construct($name, $options);
    }
}
