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
 * Abstract Named Section
 *
 * Uses a custom name
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class AbstractNamedSection extends AbstractSection
{
    /**
     * Predefined section name
     *
     * @var string
     */
    protected $sectionName;

    /**
     * Creates a Named section
     *
     * @param string $name
     * @param array  $options
     */
    public function __construct($name, array $options = array())
    {
        $this->name = $this->sectionName . ':' . trim($name);

        parent::__construct($options);
    }
}
