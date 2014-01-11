<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SupervisorctlSection extends AbstractSection
{
    protected $name = 'supervisorctl';

    protected $validOptions = array(
        'serverurl'    => 'string',
        'username'     => 'string',
        'password'     => 'string',
        'prompt'       => 'string',
        'history_file' => 'string',
    );
}
