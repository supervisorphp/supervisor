<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\XmlRpc;

use Indigo\Http\Adapter;
use Psr\Http\Message\OutgoingRequestInterface as Request;

/**
 * Handles authentication
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Authentication implements Adapter
{
    use \Indigo\Http\Adapter\Decorator;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @param Adapter $adapter
     * @param string  $username
     * @param string  $password
     */
    public function __construct(Adapter $adapter, $username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->adapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Request $request)
    {
        $request->setHeader('Authorization', 'Basic '.base64_encode(sprintf('%s:%s', $this->username, $this->password)));

        return $this->adapter->send($request);
    }
}
