<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventListenerSection extends ProgramSection
{
	public function __construct($name, array $options = array())
	{
		parent::__construct($name, $options);

		$this->name = 'eventlistener:' . trim($name);
	}

	protected function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		parent::setDefaultOptions($resolver);

		$resolver->setOptional(array(
			'buffer_size',
			'events',
			'result_handler',
		));
	}
}
