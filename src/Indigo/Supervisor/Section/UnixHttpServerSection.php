<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UnixHttpServerSection extends AbstractSection
{
    protected $name = 'unix_http_server';

    protected $validOptions = array(
        'file'     => 'string',
        'chmod'    => 'integer',
        'chown'    => 'string',
        'username' => 'string',
        'password' => 'string',
    );
}
