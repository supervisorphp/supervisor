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
    protected $requiredOverride = [
        'socket'  => 'string',
    ];

    /**
     * {@inheritdoc}
     */
    protected $optionalOverride = [
        'socket_owner' => 'string',
        'socket_mode'  => 'integer',
    ];

    /**
     * @param string $name
     * @param []     $options
     *
     * @codeCoverageIgnore
     */
    public function __construct($name, array $options = [])
    {
        $this->optionalOptions = array_merge($this->optionalOptions, $this->optionalOverride);
        $this->requiredOptions = array_merge($this->requiredOptions, $this->requiredOverride);

        parent::__construct($name, $options);
    }
}
