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
 * Unix HTTP Section
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class UnixHttpServer extends AbstractSection
{
    /**
     * {@inheritdocs}
     */
    protected $name = 'unix_http_server';

    /**
     * {@inheritdocs}
     */
    protected $optionalOptions = array(
        'file'     => 'string',
        'chmod'    => array('integer', 'string'),
        'chown'    => 'string',
        'username' => 'string',
        'password' => 'string',
    );
}
