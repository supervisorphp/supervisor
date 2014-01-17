<?php

namespace Indigo\Supervisor;

use Indigo\Supervisor\Section\SectionInterface;

class Configuration
{
    /**
     * Config sections
     *
     * @var array
     */
    protected $sections = array();

    protected $mapSections = array(
        'eventlistener'    => 'Indigo\\Supervisor\\Section\\EventListenerSection',
        'fcgi-program'     => 'Indigo\\Supervisor\\Section\\FcgiProgramSection',
        'group'            => 'Indigo\\Supervisor\\Section\\GroupSection',
        'include'          => 'Indigo\\Supervisor\\Section\\IncludeSection',
        'inet_http_server' => 'Indigo\\Supervisor\\Section\\InetHttpServerSection',
        'program'          => 'Indigo\\Supervisor\\Section\\ProgramSection',
        'supervisorctl'    => 'Indigo\\Supervisor\\Section\\SupervisorctlSection',
        'supervisord'      => 'Indigo\\Supervisor\\Section\\SupervisordSection',
        'unix_http_server' => 'Indigo\\Supervisor\\Section\\UnixHttpServerSection',
    );

    /**
     * Add a section
     *
     * @param  SectionInterface $section
     * @return Configuration
     */
    public function addSection(SectionInterface $section)
    {
        $this->sections[$section->getName()] = $section;

        return $this;
    }

    /**
     * Remove a section by name
     *
     * @param  string  $section
     * @return boolean
     */
    public function removeSection($section)
    {
        if (array_key_exists($section, $this->sections)) {
            unset($this->sections[$section]);

            return true;
        }

        return false;
    }

    /**
     * Get a specific section by name or all
     *
     * @param  string $section
     * @return mixed
     */
    public function getSection($section = null)
    {
        if (is_null($section)) {
            return $this->sections;
        } elseif (array_key_exists($section, $this->sections)) {
            return $this->sections[$section];
        }
    }

    /**
     * Render configuration
     *
     * @return string
     */
    public function render()
    {
        $output = '';

        foreach ($this->sections as $name => $section) {
            // Only continue processing this section if there are options in it
            if ($options = $section->getOptions()) {
                $output .= $this->renderSection($name, $options);
            }
        }

        return $output;
    }

    /**
     * Render section
     *
     * @param  string $name
     * @param  array  $section
     * @return string
     */
    protected function renderSection($name, array $section)
    {
        $output = "[$name]\n";

        foreach ($section as $key => $value) {
            is_array($value) and $value = implode(',', $value);
            $output .= "$key = $value\n";
        }

        // Write a linefeed after sections
        $output .= "\n";

        return $output;
    }

    /**
     * Parse INI file
     *
     * @param  string        $file
     * @return Configuration
     */
    public function parseFile($file)
    {
        $ini = parse_ini_file($file, true);
        $this->parseIni($ini);

        return $this;
    }

    /**
     * Parse INI string
     *
     * @param  string        $string
     * @return Configuration
     */
    public function parseString($string)
    {
        $ini = parse_ini_string($string, true);
        $this->parseIni($ini);

        return $this;
    }

    /**
     * Parse INI array
     *
     * @param  array  $ini
     */
    protected function parseIni(array $ini)
    {
        foreach ($ini as $name => $section) {
            $name = explode(':', $name);
            if (array_key_exists($name[0], $this->mapSections)) {
                if (!empty($name[1])) {
                    $section = new $this->mapSections[$name[0]]($name[1], $section);
                } else {
                    $section = new $this->mapSections[$name[0]]($section);
                }

                $this->addSection($section);
            }
        }
    }

    /**
     * Alias to render()
     */
    public function __tostring()
    {
        return $this->render();
    }
}
