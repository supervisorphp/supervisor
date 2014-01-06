<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

class ProgramSection extends AbstractSection
{
    public function __construct($name, array $options = array())
    {
        $this->resolveOptions($options);

        $this->name = 'program:' . trim($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('command'))
            ->setOptional(array(
                'process_name',
                'numprocs',
                'numprocs_start',
                'priority',
                'autostart',
                'autorestart',
                'startsecs',
                'startretries',
                'exitcodes',
                'stopsignal',
                'stopwaitsecs',
                'stopasgroup',
                'killasgroup',
                'user',
                'redirect_stderr',
                'stdout_logfile',
                'stdout_logfile_maxbytes',
                'stdout_logfile_backups',
                'stdout_capture_maxbytes',
                'stdout_events_enabled',
                'stdout_syslog',
                'stderr_logfile',
                'stderr_logfile_maxbytes',
                'stderr_logfile_backups',
                'stderr_capture_maxbytes',
                'stderr_events_enabled',
                'stderr_syslog',
                'environment',
                'directory',
                'umask',
                'serverurl',
            ))->setAllowedTypes(array(
                'command'                 => 'string'
                'process_name'            => 'string',
                'numprocs'                => 'integer',
                'numprocs_start'          => 'integer',
                'priority'                => 'integer',
                'autostart'               => 'bool',
                'autorestart'             => array('bool', 'string'),
                'startsecs'               => 'integer',
                'startretries'            => 'integer',
                'exitcodes'               => 'array',
                'stopsignal'              => 'string',
                'stopwaitsecs'            => 'integer',
                'stopasgroup'             => 'bool',
                'killasgroup'             => 'bool',
                'user'                    => 'string',
                'redirect_stderr'         => 'bool',
                'stdout_logfile'          => 'string',
                'stdout_logfile_maxbytes' => array('integer', 'string'),
                'stdout_logfile_backups'  => 'integer',
                'stdout_capture_maxbytes' => array('integer', 'string'),
                'stdout_events_enabled'   => 'bool',
                'stdout_syslog'           => 'bool',
                'stderr_logfile'          => 'bool',
                'stderr_logfile_maxbytes' => array('integer', 'string'),
                'stderr_logfile_backups'  => 'integer',
                'stderr_capture_maxbytes' => array('integer', 'string'),
                'stderr_events_enabled'   => 'bool',
                'stderr_syslog'           => 'bool',
                'environment'             => 'array',
                'directory'               => 'string',
                'umask'                   => 'integer',
                'serverurl'               => 'string',
            ))->setAllowedValues(array(
                'autorestart' => array(true, false, 'unexpected'),
                'stopsignal' => array('TERM', 'HUP', 'INT', 'QUIT', 'KILL', 'USR1', 'USR2'),
            ))->setNormalizers(array(
                'environment' => function (Options $options, $value) {
                    foreach ($value as $key => $val) {
                        $value[$key] .= strtoupper($key) . '="' . $val . '"';
                    }

                    return implode(',', $value);
                },
            ));
    }
}
