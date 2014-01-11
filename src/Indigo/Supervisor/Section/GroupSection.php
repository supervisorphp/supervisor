<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupSection extends AbstractSection
{
    protected $requiredOptions = array(
        'programs' => 'array',
    );

    protected $optionalOptions = array(
        'priority' => 'integer',
    );

    public function __construct($name, array $options = array())
    {
        $this->setOptions($options);

        $this->name = 'group:' . trim($name);
    }
}
