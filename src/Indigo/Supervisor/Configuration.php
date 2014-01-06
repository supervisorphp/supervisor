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
            // only continue processing this section if there are options in it
            if (!$options = $section->getOptions()) {
                continue;
            }

            // write a linefeed before sections
            empty($output) or $output .= "\n";

            $output .= "[$name]\n";

            foreach ($options as $key => $value) {
                $output .= "$key = $value\n";
            }
        }

        return $output;
    }

    /**
     * Alias to render()
     */
    public function __tostring()
    {
        return $this->render();
    }
}
