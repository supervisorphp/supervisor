<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventListenerSection extends ProgramSection
{
    public function __construct($name, array $options = array())
    {
        $this->resolveOptions($options);

        $this->name = 'eventlistener:' . trim($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setOptional(array(
            'buffer_size',
            'events',
            'result_handler',
        ))->setAllowedTypes(array(
            'buffer_size'    => 'integer',
            'events'         => 'array',
            'result_handler' => 'string',
        ));
    }
}
