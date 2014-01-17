<?php

namespace Indigo\Supervisor\Section;

interface SectionInterface
{
    /**
     * Get the section name
     * Should be set explicitly for single sections (eg. supervisord)
     *
     * @return string
     */
    public function getName();

    /**
     * Get the options as an array
     *
     * @return array
     */
    public function getOptions();

    /**
     * Set an array of options
     *
     * @return SectionInterface
     */
    public function setOptions(array $options);
}
