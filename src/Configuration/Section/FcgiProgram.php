<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Configuration\Section;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Fcgi Program section
 *
 * @link http://supervisord.org/configuration.html#fcgi-program-x-section-settings
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FcgiProgram extends Program
{
    /**
     * {@inheritdoc}
     */
    protected $sectionName = 'fcgi-program';

    /**
     * {@inheritdoc}
     */
    protected function configureProperties(OptionsResolver $resolver)
    {
        parent::configureProperties($resolver);

        $resolver
            ->setRequired('socket')
            ->setAllowedTypes('socket', 'string');

        $resolver
            ->setDefined('socket_owner')
            ->setAllowedTypes('socket_owner', 'string');

        // TODO: octal vs. decimal value
        $resolver->setDefined('socket_mode');
        $this->configureIntegerProperty('socket_mode', $resolver);
    }
}
