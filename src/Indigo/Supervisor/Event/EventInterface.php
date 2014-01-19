<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) IndigoPHP Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Event;

interface EventInterface
{
    public function getHeader($key = null, $default = null);

    public function setHeader(array $header);

    public function getPayload($key = null, $default = null);

    public function setPayload(array $payload);

    public function getBody();

    public function setBody($body);
}
