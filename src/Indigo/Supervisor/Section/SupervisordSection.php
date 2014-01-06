<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

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
            'umask',
            'nodaemon',
            'minfds',
            'minprocs',
            'nocleanup',
            'childlogdir',
            'user',
            'directory',
            'strip_ansi',
            'environment',
            'identifier',
        ))->setAllowedTypes(array(
            'logfile'          => 'string',
            'logfile_maxbytes' => array('integer', 'string'),
            'logfile_backups'  => 'integer',
            'loglevel'         => 'string',
            'pidfile'          => 'string',
            'umask'            => 'integer',
            'nodaemon'         => 'bool',
            'minfds'           => 'integer',
            'minprocs'         => 'integer',
            'nocleanup'        => 'bool',
            'childlogdir'      => 'string',
            'user'             => 'string',
            'directory'        => 'string',
            'strip_ansi'       => 'bool',
            'environment'      => array('array', 'string'),
            'identifier'       => 'string',
        ))->setAllowedTypes(array(
            'loglevel' => array('critical', 'error', 'warn', 'info', 'debug', 'trace', 'blather'),
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
