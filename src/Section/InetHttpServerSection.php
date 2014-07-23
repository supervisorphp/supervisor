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
 * Inet HTTP Server Section
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class InetHttpServerSection extends AbstractSection
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'inet_http_server';

    /**
     * {@inheritdoc}
     */
    protected $requiredOptions = array(
        'port' => array('string', 'integer'),
    );

    /**
     * {@inheritdoc}
     */
    protected $optionalOptions = array(
        'username' => 'string',
        'password' => 'string',
    );

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
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
