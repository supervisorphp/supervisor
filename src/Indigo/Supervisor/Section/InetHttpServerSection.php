<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InetHttpServerSection extends AbstractSection
{
    protected $name = 'inet_http_server';

    protected $validOptions = array(
        'username' => 'string',
        'password' => 'string',
    );

    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
            'port'
        ))->setAllowedTypes(array(
            'port'     => 'integer',
        ));
    }
}
