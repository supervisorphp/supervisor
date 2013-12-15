<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupSection extends AbstractSection
{
	public function __construct($name, array $options = array())
	{
		$this->name = 'group:' . trim($name);

		parent::__construct($options);
	}

	protected function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setRequired(array('programs'));

		$resolver->setOptional(array('priority'));
	}
}
