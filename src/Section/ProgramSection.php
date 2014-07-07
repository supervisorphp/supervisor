<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Program Section
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class ProgramSection extends AbstractNamedSection
{
    /**
     * {@inheritdocs}
     */
    protected $sectionName = 'program';

    /**
     * {@inheritdocs}
     */
    protected $requiredOptions = array(
        'command' => 'string',
    );

    /**
     * {@inheritdocs}
     */
    protected $optionalOptions = array(
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

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setAllowedValues(array(
                'autorestart' => array(true, false, 'unexpected'),
                'stopsignal'  => array('TERM', 'HUP', 'INT', 'QUIT', 'KILL', 'USR1', 'USR2'),
            ))
            ->setNormalizers(array(
                'environment' => $this->environmentNormalizer(),
            ));
    }
}
