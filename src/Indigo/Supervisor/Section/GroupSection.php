<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) IndigoPHP Development Team
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
class GroupSection extends AbstractSection
{
    protected $requiredOptions = array(
        'programs' => 'array',
    );

    protected $optionalOptions = array(
        'priority' => 'integer',
    );

    public function __construct($name, array $options = array())
    {
        $this->setOptions($options);

        $this->name = 'group:' . trim($name);
    }
}
