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
 * RPC Interface Section
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class RpcInterfaceSection extends AbstractSection
{
    protected $optionalOptions = array(
        'supervisor.rpcinterface_factory' => 'string',
    );

    public function __construct($name, array $options = array())
    {
        $this->setOptions($options);

        $this->name = 'rpcinterface:' . trim($name);
    }
}
