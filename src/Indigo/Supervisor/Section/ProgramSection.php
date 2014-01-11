<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

class ProgramSection extends AbstractSection
{
    protected $validOptions = array(
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
        'environment'             => array('array', 'string'),
        'directory'               => 'string',
        'umask'                   => 'integer',
        'serverurl'               => 'string',
    );

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
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
            'command'
        ))->setAllowedTypes(array(
            'command' => 'string',
        ))->setAllowedValues(array(
            'autorestart' => array(true, false, 'unexpected'),
            'stopsignal' => array('TERM', 'HUP', 'INT', 'QUIT', 'KILL', 'USR1', 'USR2'),
        ))->setNormalizers(array(
            'environment' => function (Options $options, $value) {
                if (is_array($value)) {
                    $return = array();

                    foreach ($value as $key => $val) {
                        if (is_int($key)) {
                            continue;
                        }

                        $return[$key] = strtoupper($key) . '="' . $val . '"';
                    }

                    $value = implode(',', $return);
                }

                return (string) $value;
            },
        ));
    }
}
