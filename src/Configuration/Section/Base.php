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

use Indigo\Supervisor\Configuration\Section;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Abstract section with some basic implementation
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class Base implements Section
{
    /**
     * Name of section (eg. supervisord or program:test)
     *
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @var OptionsResolver[]
     */
    private static $resolversByClass = [];

    /**
     * @param array $properties
     */
    public function __construct(array $properties)
    {
        $this->setProperties($properties);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty($key)
    {
        if (isset($this->properties[$key])) {
            return $this->properties[$key];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setProperty($key, $value)
    {
        $properties = $this->properties;
        $properties[$key] = $value;

        $this->setProperties($properties);
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties)
    {
        $this->properties = $this->resolveProperties($properties);
    }

    /**
     * Resolves properties
     *
     * @param array $properties
     *
     * @return array
     */
    protected function resolveProperties(array $properties)
    {
        $class = get_class($this);

        if (!isset(self::$resolversByClass[$class])) {
            self::$resolversByClass[$class] = new OptionsResolver;
            $this->configureProperties(self::$resolversByClass[$class]);
        }

        return self::$resolversByClass[$class]->resolve($properties);
    }

    /**
     * @param OptionsResolver $resolver
     */
    abstract protected function configureProperties(OptionsResolver $resolver);

    /**
     * Values returned from INI parser are always string
     * As a workaround to this problem you can set various normalizers to optimize the values
     *
     * Note: The property should be defined first
     */

    /**
     * Configures an integer property for OptionsResolver
     *
     * @param string          $property
     * @param OptionsResolver $resolver
     */
    protected function configureIntegerProperty($property, OptionsResolver $resolver)
    {
        $resolver
            ->setAllowedTypes($property, ['integer', 'numeric'])
            ->setNormalizer($property, function (Options $options, $value) {
                return is_int($value) ? $value : intval($value);
            });
    }

    /**
     * Configures an array property for OptionsResolver
     *
     * @param string          $property
     * @param OptionsResolver $resolver
     */
    protected function configureArrayProperty($property, OptionsResolver $resolver)
    {
        $resolver
            ->setAllowedTypes($property, ['array', 'string'])
            ->setNormalizer($property, function (Options $options, $value) {
                return is_array($value) ? $value : explode(',', str_replace(' ', '', $value));
            });
    }

    /**
     * Configures a boolean property for OptionsResolver
     *
     * @param string          $property
     * @param OptionsResolver $resolver
     */
    protected function configureBooleanProperty($property, OptionsResolver $resolver)
    {
        $resolver
            ->setAllowedTypes($property, ['bool', 'string'])
            ->setAllowedValues($property, [true, false, 'true', 'false'])
            ->setNormalizer($property, function (Options $options, $value) {
                return is_bool($value) ? $value : ($value === 'true' ? true : false);
            });
    }

    /**
     * Configures an environment property for OptionsResolver
     *
     * @param OptionsResolver $resolver
     */
    protected function configureEnvironmentProperty(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined('environment')
            ->setAllowedTypes('environment', ['array', 'string'])
            ->setNormalizer('environment', function (Options $options, $value) {
                if (is_array($value)) {
                    $normalized = [];

                    foreach ($value as $key => $val) {
                        is_string($key) and $normalized[] = sprintf('%s="%s"', strtoupper($key), $val);
                    }

                    $value = implode(',', $normalized);
                }

                return $value;
            });
    }

    /**
     * Configures a byte property for OptionsResolver
     *
     * @param string          $property
     * @param OptionsResolver $resolver
     */
    protected function configureByteProperty($property, OptionsResolver $resolver)
    {
        $resolver
            ->setAllowedTypes($property, 'byte')
            ->setNormalizer($property, function (Options $options, $value) {
                return is_numeric($value) ? intval($value) : $value;
            });
    }
}
