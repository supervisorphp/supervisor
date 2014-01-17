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
 * Fcgi Program Section
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
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
