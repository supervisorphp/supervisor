<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractSection implements SectionInterface
{
	/**
	 * Options
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Name of section (eg. supervisord or program:test)
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Create a new Section instance
	 *
	 * @param array $options
	 */
	public function __construct(array $options = array())
	{
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);

        $this->options = $resolver->resolve($options);
	}

	/**
	 * Get the name of section
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get options
	 *
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}
}
