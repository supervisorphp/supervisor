<?php

namespace Indigo\Supervisor;

use Indigo\Supervisor\Section\SectionInterface;

class Configuration
{
	protected $sections = array();

	public function addSection(SectionInterface $section)
	{
		$this->sections[$section->getName()] = $section;

		return $this;
	}

	public function removeSection($section)
	{
		if (array_key_exists($section, $this->sections)) {
			unset($this->sections[$section]);
		}

		return $this;
	}

	public function render()
	{
		$output = '';

		foreach ($this->sections as $name => $section) {
			if ( ! $options = $section->getOptions()) {
				continue;
			}

			empty($output) or $output .= "\n";

			$output .= "[$name]\n";

			foreach ($options as $key => $value) {
				$output .= "$key = $value\n";
			}
		}

		return $output;
	}

	public function __tostring()
	{
		return $this->render();
	}
}
