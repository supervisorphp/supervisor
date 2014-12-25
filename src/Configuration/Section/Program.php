<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Configuration\Section;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Program section
 *
 * @link http://supervisord.org/configuration.html#program-x-section-settings
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Program extends Named
{
    /**
     * {@inheritdoc}
     */
    protected $sectionName = 'program';

    /**
     * {@inheritdoc}
     */
    protected function configureProperties(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('command')
            ->setAllowedTypes('command', 'string');

        $resolver
            ->setDefined('process_name')
            ->setAllowedTypes('process_name', 'string');

        $resolver->setDefined('numprocs');
        $this->configureIntegerProperty('numprocs', $resolver);

        $resolver->setDefined('numprocs_start');
        $this->configureIntegerProperty('numprocs_start', $resolver);

        $resolver->setDefined('priority');
        $this->configureIntegerProperty('priority', $resolver);

        $this->configureStartControlProperties($resolver);
        $this->configureStopControlProperties($resolver);

        $resolver
            ->setDefined('user')
            ->setAllowedTypes('user', 'string');

        $this->configureLogProperties($resolver);

        $this->configureEnvironmentProperty($resolver);

        $resolver
            ->setDefined('directory')
            ->setAllowedTypes('directory', 'string');

        // TODO: octal vs. decimal value
        $resolver->setDefined('umask');
        $this->configureIntegerProperty('umask', $resolver);

        $resolver
            ->setDefined('serverurl')
            ->setAllowedTypes('serverurl', 'string');
    }

    /**
     * Configures start control related properties
     *
     * @param OptionsResolver $resolver
     */
    protected function configureStartControlProperties(OptionsResolver $resolver)
    {
        $resolver->setDefined('autostart');
        $this->configureBooleanProperty('autostart', $resolver);


        $resolver
            ->setDefined('autorestart')
            ->setAllowedTypes('autorestart', ['bool', 'string'])
            ->setAllowedValues('autorestart', [true, false, 'true', 'false', 'unexpected'])
            ->setNormalizer('autorestart', function (Options $options, $value) {
                return (is_bool($value) or $value === 'unexpected') ? $value : ($value === 'true' ? true : false);
            });

        $resolver->setDefined('startsecs');
        $this->configureIntegerProperty('startsecs', $resolver);

        $resolver->setDefined('startretries');
        $this->configureIntegerProperty('startretries', $resolver);
    }

    /**
     * Configures stop control related properties
     *
     * @param OptionsResolver $resolver
     */
    protected function configureStopControlProperties(OptionsResolver $resolver)
    {
        $resolver->setDefined('exitcodes');
        $this->configureArrayProperty('exitcodes', $resolver);

        $resolver
            ->setDefined('stopsignal')
            ->setAllowedTypes('stopsignal', 'string')
            ->setAllowedValues('stopsignal', ['TERM', 'HUP', 'INT', 'QUIT', 'KILL', 'USR1', 'USR2']);

        $resolver->setDefined('stopwaitsecs');
        $this->configureIntegerProperty('stopwaitsecs', $resolver);

        $resolver->setDefined('stopasgroup');
        $this->configureBooleanProperty('stopasgroup', $resolver);

        $resolver->setDefined('killasgroup');
        $this->configureBooleanProperty('killasgroup', $resolver);
    }

    /**
     * Configures log related properties
     *
     * @param OptionsResolver $resolver
     */
    protected function configureLogProperties(OptionsResolver $resolver)
    {
        $resolver->setDefined('redirect_stderr');
        $this->configureBooleanProperty('redirect_stderr', $resolver);

        $this->configureStdoutLogProperties($resolver);
        $this->configureStderrLogProperties($resolver);
    }

    /**
     * Configures stdout log related properties
     *
     * @param OptionsResolver $resolver
     */
    protected function configureStdoutLogProperties(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined('stdout_logfile')
            ->setAllowedTypes('stdout_logfile', 'string');

        $resolver->setDefined('stdout_logfile_maxbytes');
        $this->configureByteProperty('stdout_logfile_maxbytes', $resolver);

        $resolver->setDefined('stdout_logfile_backups');
        $this->configureIntegerProperty('stdout_logfile_backups', $resolver);

        $resolver->setDefined('stdout_capture_maxbytes');
        $this->configureByteProperty('stdout_capture_maxbytes', $resolver);

        $resolver->setDefined('stdout_events_enabled');
        $this->configureBooleanProperty('stdout_events_enabled', $resolver);

        $resolver->setDefined('stdout_syslog');
        $this->configureBooleanProperty('stdout_syslog', $resolver);
    }

    /**
     * Configures stderr log related properties
     *
     * @param OptionsResolver $resolver
     */
    protected function configureStderrLogProperties(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined('stderr_logfile')
            ->setAllowedTypes('stderr_logfile', 'string');

        $resolver->setDefined('stderr_logfile_maxbytes');
        $this->configureByteProperty('stderr_logfile_maxbytes', $resolver);

        $resolver->setDefined('stderr_logfile_backups');
        $this->configureIntegerProperty('stderr_logfile_backups', $resolver);

        $resolver->setDefined('stderr_capture_maxbytes');
        $this->configureByteProperty('stderr_capture_maxbytes', $resolver);

        $resolver->setDefined('stderr_events_enabled');
        $this->configureBooleanProperty('stderr_events_enabled', $resolver);

        $resolver->setDefined('stderr_syslog');
        $this->configureBooleanProperty('stderr_syslog', $resolver);
    }
}
