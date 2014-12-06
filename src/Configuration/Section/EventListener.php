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
 * Event Listener section
 *
 * @link http://supervisord.org/configuration.html#eventlistener-x-section-settings
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class EventListener extends Program
{
    /**
     * {@inheritdoc}
     */
    protected $sectionName = 'eventlistener';

    /**
     * {@inheritdoc}
     */
    protected function configureProperties(OptionsResolver $resolver)
    {
        parent::configureProperties($resolver);

        $resolver->setDefined('buffer_size');
        $this->configureIntegerProperty('buffer_size', $resolver);

        $resolver->setDefined('events');
        $this->configureArrayProperty('events', $resolver);

        $resolver
            ->setDefined('result_handler')
            ->setAllowedTypes('result_handler', 'string');
    }
}
