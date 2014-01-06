<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InetHttpServerSection extends AbstractSection
{
    protected $name = 'inet_http_server';

    public function __construct(array $options = array())
    {
        $this->resolveOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('port'))
            ->setOptional(array('username', 'password'))
            ->setAllowedTypes(array(
                'port'     => 'integer',
                'username' => 'string',
                'password' => 'string',
            ));
    }
}
