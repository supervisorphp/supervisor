<?php

namespace Indigo\Supervisor\Section;

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
