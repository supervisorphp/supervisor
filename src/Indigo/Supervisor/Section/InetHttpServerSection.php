<?php

namespace Indigo\Supervisor\Section;

class InetHttpServerSection extends AbstractSection
{
    protected $name = 'inet_http_server';

    protected $requiredOptions = array(
        'port' => 'string',
    );

    protected $optionalOptions = array(
        'username' => 'string',
        'password' => 'string',
    );
}
