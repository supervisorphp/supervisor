<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Connector;

/**
 * Checks whether connectiong to a local instance or not
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait LocalInstance
{
    /**
     * Whether Supervisor is running locally or not
     *
     * @var boolean
     */
    protected $local = false;

    /**
     * {@inheritdoc}
     */
    public function isLocal()
    {
        return $this->local;
    }
}
