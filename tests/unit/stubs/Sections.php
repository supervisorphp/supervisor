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
 * Dummy Section class
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class DummySection extends AbstractSection
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'dummy';

    /**
     * {@inheritdoc}
     */
    protected $optionalOptions = array(
        'optional' => 'integer',
    );
}

/**
 * Dummy Named Section class
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class DummyNamedSection extends AbstractNamedSection
{
    /**
     * {@inheritdoc}
     */
    protected $sectionName = 'dummy';

    /**
     * {@inheritdoc}
     */
    protected $optionalOptions = array(
        'optional' => 'integer',
    );
}
