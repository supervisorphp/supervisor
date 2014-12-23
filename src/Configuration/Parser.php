<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Configuration;

use Indigo\Supervisor\Configuration;

/**
 * Parses configuration from various sources
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Parser
{
    /**
     * Parse an input to a configuration file
     *
     * @param Configuration $configuration If null passed, it is created automatically
     *
     * @return Configuration
     */
    public function parse(Configuration $configuration = null);
}
