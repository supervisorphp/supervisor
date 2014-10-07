<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

/**
 * Include Section
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class IncludeSection extends AbstractSection
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'include';

    /**
     * {@inheritdoc}
     */
    protected $optionalOptions = [
        'files' => ['array', 'string'],
    ];

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setNormalizers([
            'files' => function (Options $options, $value) {
                if (is_array($value)) {
                    $value = implode(' ', $value);
                }

                return $value;
            }
        ]);
    }
}
