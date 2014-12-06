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
     * @var Section[]
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
     * Adds or overrides a section
     *
     * @param Section $section
     */
    public function addSection(Section $section)
    {
        $this->sections[$section->getName()] = $section;
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
     * Returns all sections
     *
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * Adds or overrides an array sections
     *
     * @param Section[] $sections
     */
    public function addSections(array $sections)
    {
        foreach ($sections as $section) {
            $this->addSection($section);
        }
    }

    /**
     * Resets Configuration
     */
    public function reset()
    {
        $this->sections = [];
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
            $output .= $this->renderSection($section);
        }

        return $output;
    }

    /**
     * Renders a section
     *
     * @param Section $section
     *
     * @return string
     */
    public function renderSection(Section $section)
    {
        $output = '['.$section->getName()."]\n";

        foreach ($section->getProperties() as $key => $value) {
            $value = $this->normalizeValue($value);
            $output .= "$key = $value\n";
        }

        // Write a linefeed after sections
        $output .= "\n";

        return $output;
    }

    /**
     * Normalize value to valid INI format
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function normalizeValue($value)
    {
        if (is_array($value)) {
            return implode(',', $value);
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return $value;
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
        $ini = parse_ini_string($string, true, INI_SCANNER_RAW);
        $this->parseIni($ini);
    }

    /**
     * Parses an INI array
     *
     * @param array $ini
     *
     * @throws Exception\UnknownSection If section is not found in the section map
     */
    protected function parseIni(array $ini)
    {
        foreach ($ini as $name => $section) {
            $section = $this->parseIniSection($name, $section);
            $this->addSection($section);
        }
    }

    /**
     * Parses an individual section
     *
     * @param string $name
     * @param array  $section Array representation of section
     *
     * @return Section
     */
    protected function parseIniSection($name, array $section)
    {
        $name = explode(':', $name);

        if (!array_key_exists($name[0], $this->sectionMap)) {
            throw new Exception\UnknownSection($name[0]);
        }

        $class = $this->sectionMap[$name[0]];

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
