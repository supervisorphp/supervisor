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
 * RPC Interface section
 *
 * @link http://supervisord.org/configuration.html#rpcinterface-x-section-settings
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class RpcInterface extends Named
{
    /**
     * {@inheritdoc}
     */
    protected $sectionName = 'rpcinterface';

    /**
     * {@inheritdoc}
     */
    protected function configureProperties(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined('supervisor.rpcinterface_factory')
            ->setAllowedTypes('supervisor.rpcinterface_factory', 'string');

        // Note: undocumented, based on examples
        $resolver->setDefined('retries');
        $this->configureIntegerProperty('retries', $resolver);
    }
}
