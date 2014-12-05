<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor;

use UnexpectedValueException;

/**
 * Supervisor configuration parser and generator
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Configuration
{
    /**
     * Config sections
     *
     * @var []
     */
    protected $sections = [];

    /**
     * Available sections
     *
     * @var []
     */
    protected $sectionMap = [
        'eventlistener'    => 'Indigo\\Supervisor\\Section\\EventListener',
        'fcgi-program'     => 'Indigo\\Supervisor\\Section\\FcgiProgram',
        'group'            => 'Indigo\\Supervisor\\Section\\Group',
        'include'          => 'Indigo\\Supervisor\\Section\\Includes',
        'inet_http_server' => 'Indigo\\Supervisor\\Section\\InetHttpServer',
        'program'          => 'Indigo\\Supervisor\\Section\\Program',
        'supervisorctl'    => 'Indigo\\Supervisor\\Section\\Supervisorctl',
        'supervisord'      => 'Indigo\\Supervisor\\Section\\Supervisord',
        'unix_http_server' => 'Indigo\\Supervisor\\Section\\UnixHttpServer',
        'rpcinterface'     => 'Indigo\\Supervisor\\Section\\RpcInterface',
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
     * Returns a specific section by name
     *
     * @param string $section
     *
     * @return Section|null
     */
    public function getSection($section)
    {
        if ($this->hasSection($section)) {
            return $this->sections[$section];
        }
    }

    /**
     * Checks whether section exists in Configuration
     *
     * @param string $section
     *
     * @return boolean
     */
    public function hasSection($section)
    {
        return array_key_exists($section, $this->sections);
    }

    /**
     * Returns all sections
     *
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * Adds or overrides a section
     *
     * @param Section $section
     */
    public function addSection(Section $section)
    {
        $this->sections[$section->getName()] = $section;
    }

    /**
     * Adds or overrides an array sections
     *
     * @param [] $sections
     */
    public function addSections(array $sections)
    {
        foreach ($sections as $section) {
            $this->addSection($section);
        }
    }

    /**
     * Removes a section by name
     *
     * @param string $section
     *
     * @return boolean
     */
    public function removeSection($section)
    {
        if ($has = $this->hasSection($section)) {
            unset($this->sections[$section]);
        }

        return $has;
    }

    /**
     * Resets Configuration
     *
     * @return [] Array of previous sections
     */
    public function reset()
    {
        $sections = $this->sections;
        $this->sections = [];

        return $sections;
    }

    /**
     * Returns rendered configuration
     *
     * @return string
     */
    public function render()
    {
        $output = '';

        foreach ($this->sections as $name => $section) {
            // Only continue processing this section if there are options in it
            if ($section->hasOptions()) {
                $output .= $this->renderSection($section);
            }
        }

        return $output;
    }

    /**
     * Renders a section
     *
     * @param string $name
     * @param array  $section
     *
     * @return string
     */
    public function renderSection(Section $section)
    {
        $output = '['.$section->getName()."]\n";

        foreach ($section->getOptions() as $key => $value) {
            is_array($value) and $value = implode(',', $value);
            $output .= "$key = $value\n";
        }

        // Write a linefeed after sections
        $output .= "\n";

        return $output;
    }

    /**
     * Parses an INI file
     *
     * @param string $file
     */
    public function parseFile($file)
    {
        $ini = parse_ini_file($file, true, INI_SCANNER_RAW);
        $this->parseIni($ini);
    }

    /**
     * Parses an INI string
     *
     * @param string $string
     */
    public function parseString($string)
    {
        $ini = parse_ini_string($string, true);
        $this->parseIni($ini);
    }

    /**
     * Parses an INI array
     *
     * @param [] $ini
     */
    protected function parseIni(array $ini)
    {
        foreach ($ini as $name => $section) {
            $name = explode(':', $name);
            if (array_key_exists($name[0], $this->sectionMap)) {
                $section = $this->parseIniSection($this->sectionMap[$name[0]], $name, $section);
                $this->addSection($section);
            } else {
                throw new UnexpectedValueException('Unexpected section name: ' . $name[0]);
            }
        }
    }

    /**
     * Parses an individual section
     *
     * @param string $class   Name of Section class
     * @param mixed  $name    Section name or array of name and option
     * @param []     $section Array representation of section
     *
     * @return Section
     */
    protected function parseIniSection($class, array $name, array $section)
    {
        if (isset($name[1])) {
            $section = new $class($name[1], $section);
        } else {
            $section = new $class($section);
        }

        return $section;
    }

    /**
     * Returns rendered configuration
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
