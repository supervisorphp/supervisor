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
 * RPC Interface Section
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class RpcInterfaceSection extends AbstractNamedSection
{
    /**
     * {@inheritdocs}
     */
    protected $sectionName = 'rpcinterface';

    /**
     * {@inheritdocs}
     */
    protected $optionalOptions = array(
        'supervisor.rpcinterface_factory' => 'string',
    );
}
