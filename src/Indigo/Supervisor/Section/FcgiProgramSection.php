<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FcgiProgramSection extends ProgramSection
{
    protected $validOptionsOverride = array(
        'socket_owner' => 'string',
        'socket_mode'  => 'integer',
    );

    public function __construct($name, array $options = array())
    {
        $this->validOptions = array_merge($this->validOptions, $this->validOptionsOverride);
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
        ))->setAllowedTypes(array(
            'socket' => 'string',
        ));
    }
}
