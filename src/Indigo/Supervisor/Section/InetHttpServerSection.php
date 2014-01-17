<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

class InetHttpServerSection extends AbstractSection
{
    protected $name = 'inet_http_server';

    protected $requiredOptions = array(
        'port' => array('string', 'integer'),
    );

    protected $optionalOptions = array(
        'username' => 'string',
        'password' => 'string',
    );

    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setNormalizers(array(
            'port' => function (Options $options, $value) {
                is_int($value) and $value = '*:' . $value;

                return $value;
            },
        ));
    }
}
