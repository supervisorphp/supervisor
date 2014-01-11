<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupSection extends AbstractSection
{
    protected $validOptions = array(
        'priority' => 'integer',
    );

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
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
            'programs'
        ))->setAllowedTypes(array(
            'programs' => 'array',
        ));
    }
}
