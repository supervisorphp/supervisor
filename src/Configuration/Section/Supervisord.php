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

/**
 * Supervisord section
 *
 * @link http://supervisord.org/configuration.html#supervisord-section-settings
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Supervisord extends Base
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'supervisord';

    protected function configureProperties(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined('logfile')
            ->setAllowedTypes('logfile', 'string');

        $resolver->setDefined('logfile_maxbytes');
        $this->configureByteProperty('logfile_maxbytes', $resolver);

        $resolver->setDefined('logfile_backups');
        $this->configureIntegerProperty('logfile_backups', $resolver);

        $resolver
            ->setDefined('loglevel')
            ->setAllowedTypes('loglevel', 'string')
            ->setAllowedValues('loglevel', ['critical', 'error', 'warn', 'info', 'debug', 'trace', 'blather']);

        $resolver
            ->setDefined('pidfile')
            ->setAllowedTypes('pidfile', 'string');

        // TODO: octal vs. decimal value
        $resolver->setDefined('umask');
        $this->configureIntegerProperty('umask', $resolver);

        $resolver->setDefined('nodaemon');
        $this->configureBooleanProperty('nodaemon', $resolver);

        $resolver->setDefined('minfds');
        $this->configureIntegerProperty('minfds', $resolver);

        $resolver->setDefined('minprocs');
        $this->configureIntegerProperty('minprocs', $resolver);

        $resolver->setDefined('nocleanup');
        $this->configureBooleanProperty('nocleanup', $resolver);

        $resolver
            ->setDefined('childlogdir')
            ->setAllowedTypes('childlogdir', 'string');

        $resolver
            ->setDefined('user')
            ->setAllowedTypes('user', 'string');

        $resolver
            ->setDefined('directory')
            ->setAllowedTypes('directory', 'string');

        $resolver->setDefined('strip_ansi');
        $this->configureBooleanProperty('strip_ansi', $resolver);

        $this->configureEnvironmentProperty($resolver);

        $resolver
            ->setDefined('identifier')
            ->setAllowedTypes('identifier', 'string');
    }
}
