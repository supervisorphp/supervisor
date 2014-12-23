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

/**
 * Local Instance Connector Stub
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class LocalInstance
{
    use \Indigo\Supervisor\Connector\LocalInstance;

    public function __construct($local)
    {
        $this->local = (bool) $local;
    }
}
