<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SupervisordSection extends AbstractSection
{
	protected $name = 'supervisord';

	/**
	 * {@inheritdoc}
	 */
	protected function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setOptional(array(
			'logfile',
			'logfile_maxbytes',
			'logfile_backups',
			'loglevel',
			'pidfile',
			'nodaemon',
			'minfds',
			'minprocs',
			'umask',
			'user',
			'identifier',
			'directory',
			'nocleanup',
			'childlogdir',
			'strip_ansi',
			'environment',
		));
	}
}
