<?php

namespace Indigo\Supervisor\Section;

use Codeception\TestCase\Test;

/**
 * Tests for Sections
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class SectionTest extends Test
{
    protected $configuration;

    public function setUp()
    {
        $this->configuration = new \Indigo\Supervisor\Configuration;
    }

    public function provider()
    {
        return array(
            new EventListenerSection('test', array(
                'command' => 'cat /path/to/file',
            )),
            new FcgiProgramSection('test', array(
                'socket'  => '/path/to/socket',
                'command' => 'cat /path/to/file',
            )),
            new GroupSection('test', array(
                'programs' => array(
                    'test',
                    'empty',
                ),
            )),
            new IncludeSection(array(
                'files' => array('/etc/supervisord/conf.d/*'),
            )),
            new InetHttpServerSection(array(
                'port' => 9001,
            )),
            new ProgramSection('test', array(
                'command' => 'cat /path/to/file',
                'environment' => array(
                    'KEY' => 'value',
                    'fake_value',
                ),
            )),
            new ProgramSection('test', array(
                'command'    => 'cat /path/to/file',
                'stopsignal' => 'TERM',
            )),
            new SupervisorctlSection,
            new SupervisordSection(array(
                'environment' => array(
                    'KEY' => 'value',
                    'fake_value'
                ),
            )),
            new SupervisordSection(array(
                'loglevel' => 'warn',
            )),
            new UnixHttpServerSection,
            new RpcInterfaceSection('test'),
        );
    }

    public function realProvider()
    {
        $provider = array();
        foreach ($this->provider() as $section) {
            $provider[] = array($section);
        }

        return $provider;
    }

    public function testConfig()
    {
        foreach ($this->provider() as $section) {
            $this->assertInstanceOf(
                'Indigo\\Supervisor\\Configuration',
                $this->configuration->addSection($section)
            );
        }

        return $this->configuration;
    }

    public function testSection()
    {
        foreach ($this->provider() as $section) {
            $this->assertInstanceOf(
                'Indigo\\Supervisor\\Section\\SectionInterface',
                $section
            );
        }
    }

    /**
     * @expectedException Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     * @dataProvider realProvider
     */
    public function testFaultySection($section)
    {
        $options = array(
            'fake' => null,
        );

        $section->setOptions($options);
    }

    /**
     * @depends testConfig
     */
    public function testRender($configuration)
    {
        $configuration->render();
    }
}
