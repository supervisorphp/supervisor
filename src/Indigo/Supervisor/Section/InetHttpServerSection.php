<?php

namespace Indigo\Supervisor\Section;

class InetHttpServerSection extends AbstractSection
{
    protected $name = 'inet_http_server';

    protected $requiredOptions = array(
        'port' => 'integer',
    );

    protected $optionalOptions = array(
        'username' => 'string',
        'password' => 'string',
    );
}
