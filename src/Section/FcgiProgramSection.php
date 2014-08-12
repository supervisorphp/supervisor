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
 * Fcgi Program Section
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FcgiProgramSection extends ProgramSection
{
    /**
     * {@inheritdoc}
     */
    protected $sectionName = 'fcgi-program';

    /**
     * {@inheritdoc}
     */
    protected $requiredOverride = array(
        'socket'  => 'string',
    );

    /**
     * {@inheritdoc}
     */
    protected $optionalOverride = array(
        'socket_owner' => 'string',
        'socket_mode'  => 'integer',
    );

    /**
     * Creates an Fcgi-Program section
     *
     * @param string $name
     * @param array  $options
     *
     * @codeCoverageIgnore
     */
    public function __construct($name, array $options = array())
    {
        $this->optionalOptions = array_merge($this->optionalOptions, $this->optionalOverride);
        $this->requiredOptions = array_merge($this->requiredOptions, $this->requiredOverride);

        parent::__construct($name, $options);
    }
}
