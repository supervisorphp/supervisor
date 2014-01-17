<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SupervisordSection extends AbstractSection
{
    protected $name = 'supervisord';

    protected $optionalOptions = array(
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
    );

    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setAllowedValues(array(
            'loglevel' => array('critical', 'error', 'warn', 'info', 'debug', 'trace', 'blather'),
        ))->setNormalizers(array(
            'environment' => $this->environmentNormalizer(),
        ));
    }
}
