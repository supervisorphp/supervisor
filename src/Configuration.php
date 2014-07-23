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

use Indigo\Supervisor\Section\SectionInterface;
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
     * @var array
     */
    protected $sections = array();

    /**
     * Available sections
     *
     * @var array
     */
    protected $sectionMap = array(
        'eventlistener'    => 'Indigo\\Supervisor\\Section\\EventListenerSection',
        'fcgi-program'     => 'Indigo\\Supervisor\\Section\\FcgiProgramSection',
        'group'            => 'Indigo\\Supervisor\\Section\\GroupSection',
        'include'          => 'Indigo\\Supervisor\\Section\\IncludeSection',
        'inet_http_server' => 'Indigo\\Supervisor\\Section\\InetHttpServerSection',
        'program'          => 'Indigo\\Supervisor\\Section\\ProgramSection',
        'supervisorctl'    => 'Indigo\\Supervisor\\Section\\SupervisorctlSection',
        'supervisord'      => 'Indigo\\Supervisor\\Section\\SupervisordSection',
        'unix_http_server' => 'Indigo\\Supervisor\\Section\\UnixHttpServerSection',
        'rpcinterface'     => 'Indigo\\Supervisor\\Section\\RpcInterfaceSection',
    );

    /**
     * Adds or overrides default section map
     *
     * @param string $section
     * @param string $className
     *
     * @return this
     */
    public function addSectionMap($section, $className)
    {
        $this->sectionMap[$section] = $className;

        return $this;
    }

    /**
     * Returns a specific section by name
     *
     * @param string $section
     *
     * @return SectionInterface|null
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
     * @param SectionInterface $section
     *
     * @return this
     */
    public function addSection(SectionInterface $section)
    {
        $this->sections[$section->getName()] = $section;

        return $this;
    }

    /**
     * Adds or overrides an array sections
     *
     * @param array $sections
     *
     * @return this
     */
    public function addSections(array $sections)
    {
        foreach ($sections as $section) {
            $this->addSection($section);
        }

        return $this;
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
     * @return array Array of previous sections
     */
    public function reset()
    {
        $sections = $this->sections;
        $this->sections = array();

        return $sections;
    }

    /**
     * Renders configuration
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
    public function renderSection(SectionInterface $section)
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
     *
     * @return this
     */
    public function parseFile($file)
    {
        $ini = parse_ini_file($file, true);
        $this->parseIni($ini);

        return $this;
    }

    /**
     * Parses an INI string
     *
     * @param string $string
     *
     * @return this
     */
    public function parseString($string)
    {
        $ini = parse_ini_string($string, true);
        $this->parseIni($ini);

        return $this;
    }

    /**
     * Parses an INI array
     *
     * @param array $ini
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
     * @param  string $class   Name of SectionInterface class
     * @param  mixed  $name    Section name or array of name and option
     * @param  array  $section Array representation of section
     *
     * @return SectionInterface
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
     * Alias to render()
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
