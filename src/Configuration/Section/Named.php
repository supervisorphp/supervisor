<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Configuration\Section;

/**
 * Section name contains identifier
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class Named extends Base
{
    /**
     * Predefined section name
     *
     * @var string
     */
    protected $sectionName;

    /**
     * @param string $name
     * @param array  $properties
     */
    public function __construct($name, array $properties = [])
    {
        $this->name = $this->sectionName . ':' . trim($name);

        parent::__construct($properties);
    }
}
