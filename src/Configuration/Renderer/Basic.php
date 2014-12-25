<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Configuration\Renderer;

use Indigo\Supervisor\Configuration\Renderer;
use Indigo\Supervisor\Configuration;
use Indigo\Supervisor\Configuration\Section;

/**
 * Provides basic logic for rendering
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Basic implements Renderer
{
    /**
     * {@inheritdoc}
     */
    public function render(Configuration $configuration)
    {
        $output = '';

        foreach ($configuration->getSections() as $name => $section) {
            $output .= $this->renderSection($section);
        }

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function renderSection(Section $section)
    {
        $output = sprintf("[%s]\n", $section->getName());

        foreach ($section->getProperties() as $key => $value) {
            $value = $this->normalizeValue($value);
            $output .= sprintf("%s = %s\n", $key, $value);
        }

        // Write a linefeed after sections
        $output .= "\n";

        return $output;
    }

    /**
     * Normalize value to valid INI format
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function normalizeValue($value)
    {
        if (is_array($value)) {
            return implode(',', $value);
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return $value;
    }
}
