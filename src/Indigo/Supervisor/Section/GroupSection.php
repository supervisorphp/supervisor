<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupSection extends AbstractSection
{
    public function __construct($name, array $options = array())
    {
        $this->resolveOptions($options);

        $this->name = 'group:' . trim($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'programs'
        ))->setOptional(array(
            'priority'
        ))->setAllowedTypes(array(
            'programs' => 'array',
            'priority' => 'integer',
        ));
    }
}
