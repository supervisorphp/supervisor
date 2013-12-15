<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FcgiProgramSection extends ProgramSection
{
	public function __construct($name, array $options = array())
	{
		parent::__construct($name, $options);

		$this->name = 'fcgi' . $this->name;
	}

	protected function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		parent::setDefaultOptions($resolver);

		$resolver->setRequired(array('socket'));

		$resolver->setOptional(array('socket_owner', 'socket_mode'));
	}
}
