<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Stub;

use Indigo\Supervisor\Configuration\Section\Base;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Section Stub
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Section extends Base
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'test';

    /**
     * {@inheritdoc}
     */
    protected function configureProperties(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined('key')
            ->setAllowedTypes('key', 'string');

        $this->configureEnvironmentProperty($resolver);
    }
}
