<?php

namespace Indigo\Supervisor\Section;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
     * Resolve options
     *
     * @param  array  $options
     * @return array Resolved options
     */
    protected function resolveOptions(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);

        return $this->options = $resolver->resolve($options);
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
     * Set default options
     *
     * @param OptionsResolverInterface $resolver
     */
    abstract protected function setDefaultOptions(OptionsResolverInterface $resolver);
}
