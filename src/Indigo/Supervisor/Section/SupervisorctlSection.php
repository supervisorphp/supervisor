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
 * Supervisorctl Section
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class SupervisorctlSection extends AbstractSection
{
    protected $name = 'supervisorctl';

    protected $optionalOptions = array(
        'serverurl'    => 'string',
        'username'     => 'string',
        'password'     => 'string',
        'prompt'       => 'string',
        'history_file' => 'string',
    );
}
