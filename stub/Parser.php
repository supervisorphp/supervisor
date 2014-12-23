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

use Indigo\Supervisor\Configuration\Parser\Base;
use Indigo\Supervisor\Configuration;

/**
 * Configuration Parser Stub
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Parser extends Base
{
    /**
     * {@inheritdoc}
     */
    public function parse(Configuration $configuration = null)
    {
        // noop
    }
}
