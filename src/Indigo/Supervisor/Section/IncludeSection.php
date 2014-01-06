<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

class IncludeSection extends AbstractSection
{
    protected $name = 'include';

    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'files'
        ))->setAllowedTypes(array(
            'files' => array('array', 'string')
        ))->setNormalizers(array(
            'files' => function (Options $options, $value) {
                return implode(' ', $value);
            }
        ));
    }
}
