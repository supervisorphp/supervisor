<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FcgiProgramSection extends ProgramSection
{
    public function __construct($name, array $options = array())
    {
        $this->resolveOptions($options);

        $this->name = 'fcgi-program:' . trim($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
            'socket'
        ))->setOptional(array(
            'socket_owner',
            'socket_mode'
        ))->setAllowedTypes(array(
            'socket'       => 'string',
            'socket_owner' => 'string',
            'socket_mode'  => 'integer',
        ));
    }
}
