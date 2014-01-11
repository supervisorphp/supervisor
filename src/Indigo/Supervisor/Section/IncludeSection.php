<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

class IncludeSection extends AbstractSection
{
    protected $name = 'include';

    protected $optionalOptions = array(
        'files' => array('array', 'string'),
    );

    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setNormalizers(array(
            'files' => function (Options $options, $value) {
                if (is_array($value)) {
                    $value = implode(' ', $value);
                }
                return $value;
            }
        ));
    }
}
