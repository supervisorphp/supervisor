<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Configuration\Parser;

use Indigo\Supervisor\Configuration\Parser;
use Indigo\Supervisor\Configuration;
use Indigo\Supervisor\Exception\UnknownSection;

/**
 * Provides common functionality to parsers
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class Base implements Parser
{
    /**
     * Available sections
     *
     * @var array
     */
    protected $sectionMap = [
        'eventlistener'    => 'Indigo\Supervisor\Configuration\Section\EventListener',
        'fcgi-program'     => 'Indigo\Supervisor\Configuration\Section\FcgiProgram',
        'group'            => 'Indigo\Supervisor\Configuration\Section\Group',
        'include'          => 'Indigo\Supervisor\Configuration\Section\Includes',
        'inet_http_server' => 'Indigo\Supervisor\Configuration\Section\InetHttpServer',
        'program'          => 'Indigo\Supervisor\Configuration\Section\Program',
        'supervisorctl'    => 'Indigo\Supervisor\Configuration\Section\Supervisorctl',
        'supervisord'      => 'Indigo\Supervisor\Configuration\Section\Supervisord',
        'unix_http_server' => 'Indigo\Supervisor\Configuration\Section\UnixHttpServer',
        'rpcinterface'     => 'Indigo\Supervisor\Configuration\Section\RpcInterface',
    ];

    /**
     * Adds or overrides default section map
     *
     * @param string $section
     * @param string $className
     */
    public function addSectionMap($section, $className)
    {
        $this->sectionMap[$section] = $className;
    }

    /**
     * Finds a section class by name
     *
     * @param string $section
     *
     * @return string
     *
     * @throws UnknownException If section is not found in the section map
     */
    public function findSection($section)
    {
        if (!isset($this->sectionMap[$section])) {
            throw new UnknownSection($section);
        }

        return $this->sectionMap[$section];
    }

    /**
     * Parses an INI array
     *
     * Sections must be included
     *
     * @param array $ini
     *
     * @return Section[]
     */
    public function parseArray(array $ini)
    {
        $sections = [];

        foreach ($ini as $name => $section) {
            $section = $this->parseSection($name, $section);
            $sections[] = $section;
        }

        return $sections;
    }

    /**
     * Parses an individual section
     *
     * @param string $name
     * @param array  $section Array representation of section
     *
     * @return Section
     */
    public function parseSection($name, array $section)
    {
        $name = explode(':', $name, 2);

        $class = $this->findSection($name[0]);

        if (isset($name[1])) {
            return new $class($name[1], $section);
        }

        return new $class($section);
    }
}
