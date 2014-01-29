<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Section;

/**
 * Section Interface
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface SectionInterface
{
    /**
     * Get the section name
     * Should be set explicitly for single sections (eg. supervisord)
     *
     * @return string
     */
    public function getName();

    /**
     * Get the options as an array
     *
     * @return array
     */
    public function getOptions();

    /**
     * Set an array of options
     *
     * @return SectionInterface
     */
    public function setOptions(array $options);
}
