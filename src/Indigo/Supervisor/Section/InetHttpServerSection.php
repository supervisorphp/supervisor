<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InetHttpServerSection extends AbstractSection
{
	protected $name = 'inet_http_server';

	/**
	 * {@inheritdoc}
	 */
	protected function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setRequired(array('port'));

		$resolver->setOptional(array('username', 'password'));
	}
}
