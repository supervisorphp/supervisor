<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SupervisorctlSection extends AbstractSection
{
	protected $name = 'supervisorctl';

	/**
	 * {@inheritdoc}
	 */
	protected function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setOptional(array(
			'serverurl',
			'username',
			'password',
			'prompt',
			'history_file',
		));
	}
}
