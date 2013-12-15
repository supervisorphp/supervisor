<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProgramSection extends AbstractSection
{
	public function __construct($name, array $options = array())
	{
		$this->name = 'program:' . trim($name);

		parent::__construct($options);
	}

	protected function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setRequired(array('command'));

		$resolver->setOptional(array(
			'process_name',
			'numprocs',
			'directory',
			'umask',
			'priority',
			'autostart',
			'autorestart',
			'startsecs',
			'startretries',
			'exitcodes',
			'stopsignal',
			'stopwaitsecs',
			'user',
			'redirect_stderr',
			'stdout_logfile',
			'stdout_logfile_maxbytes',
			'stdout_logfile_backups',
			'stdout_capture_maxbytes',
			'stderr_logfile',
			'stderr_logfile_maxbytes',
			'stderr_logfile_backups',
			'stderr_capture_maxbytes',
			'environment',
			'serverurl',
		));
	}
}
