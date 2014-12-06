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
 * Renders a configuration
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Renderer
{
    /**
     * Renders a configuration
     *
     * @param Configuration $configuration
     *
     * @return string
     */
    public function render(Configuration $configuration);

    /**
     * Renders a section
     *
     * @param Section $section
     *
     * @return string
     */
    public function renderSection(Section $section);
}
