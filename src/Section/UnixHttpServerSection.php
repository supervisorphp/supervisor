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
class UnixHttpServerSection extends AbstractSection
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'unix_http_server';

    /**
     * {@inheritdoc}
     */
    protected $optionalOptions = [
        'file'     => 'string',
        'chmod'    => ['integer', 'string'],
        'chown'    => 'string',
        'username' => 'string',
        'password' => 'string',
    ];
}
