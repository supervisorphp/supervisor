<?php

namespace Indigo\Supervisor\Section;

class UnixHttpServerSection extends AbstractSection
{
    protected $name = 'unix_http_server';

    protected $optionalOptions = array(
        'file'     => 'string',
        'chmod'    => array('integer', 'string'),
        'chown'    => 'string',
        'username' => 'string',
        'password' => 'string',
    );
}
