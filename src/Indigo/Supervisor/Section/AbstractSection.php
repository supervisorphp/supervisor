<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractSection implements SectionInterface
{
	protected $options = array();

	protected $name;

	public function __construct(array $options = array())
	{
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);

        $this->options = $resolver->resolve($options);
	}

	public function getName()
	{
		return $this->name;
	}

	public function getOptions()
	{
		return $this->options;
	}
}
