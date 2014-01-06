<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UnixHttpServerSection extends AbstractSection
{
    protected $name = 'unix_http_server';

    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array(
            'file',
            'chmod',
            'chown',
            'username',
            'password',
        ))->setAllowedValues(array(
            'file'     => 'string',
            'chmod'    => 'integer',
            'chown'    => 'string',
            'username' => 'string',
            'password' => 'string',
        ));
    }
}
