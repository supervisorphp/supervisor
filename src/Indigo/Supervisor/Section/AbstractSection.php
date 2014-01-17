<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

abstract class AbstractSection implements SectionInterface
{
    /**
     * Options
     *
     * @var array
     */
    protected $options = array();

    /**
     * Name of section (eg. supervisord or program:test)
     *
     * @var string
     */
    protected $name;

    /**
     * Default constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
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
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options = array())
    {
        $this->options = $this->resolveOptions($options);

        return $this;
    }

    /**
     * Resolve options
     *
     * @param  array $options
     * @return array Resolved options
     */
    protected function resolveOptions(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);

        return $resolver->resolve($options);
    }

    /**
     * Set default options
     *
     * @param OptionsResolverInterface $resolver
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        if (!empty($this->requiredOptions)) {
            $resolver->setRequired(array_keys($this->requiredOptions))
                ->setAllowedTypes($this->requiredOptions);
        }

        if (!empty($this->optionalOptions)) {
            $resolver->setOptional(array_keys($this->optionalOptions))
                ->setAllowedTypes($this->optionalOptions);
        }
    }

    protected function environmentNormalizer()
    {
        return function (Options $options, $value) {
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
        };
    }
}
