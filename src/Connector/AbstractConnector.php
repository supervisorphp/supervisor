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

use Indigo\Supervisor\Connector;

/**
 * Abstract Connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class AbstractConnector implements Connector
{
    /**
     * Whether Supervisor is local or not
     *
     * @var boolean
     */
    protected $local;

    /**
     * {@inheritdoc}
     */
    public function isLocal()
    {
        return $this->local;
    }
}
