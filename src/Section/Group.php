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
 * Group Section
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Group extends AbstractNamedSection
{
    /**
     * {@inheritdocs}
     */
    protected $sectionName = 'group';

    /**
     * {@inheritdocs}
     */
    protected $requiredOptions = array(
        'programs' => 'array',
    );

    /**
     * {@inheritdocs}
     */
    protected $optionalOptions = array(
        'priority' => 'integer',
    );
}
