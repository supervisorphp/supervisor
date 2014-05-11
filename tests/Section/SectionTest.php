<?php

namespace Indigo\Supervisor\Test\Section;

use Indigo\Supervisor\Section;

/**
 * Tests for Sections
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class SectionTest extends \PHPUnit_Framework_TestCase
{
    protected $configuration;

    public function setUp()
    {
        $this->configuration = new \Indigo\Supervisor\Configuration;
    }

    public function provider()
    {
        return array(
            new Section\EventListenerSection('test', array(
                'command' => 'cat /path/to/file',
            )),
            new Section\FcgiProgramSection('test', array(
                'socket'  => '/path/to/socket',
                'command' => 'cat /path/to/file',
            )),
            new Section\GroupSection('test', array(
                'programs' => array(
                    'test',
                    'empty',
                ),
            )),
            new Section\IncludeSection(array(
                'files' => array('/etc/supervisord/conf.d/*'),
            )),
            new Section\InetHttpServerSection(array(
                'port' => 9001,
            )),
            new Section\ProgramSection('test', array(
                'command' => 'cat /path/to/file',
                'environment' => array(
                    'KEY' => 'value',
                    'fake_value',
                ),
            )),
            new Section\ProgramSection('test', array(
                'command'    => 'cat /path/to/file',
                'stopsignal' => 'TERM',
            )),
            new Section\SupervisorctlSection,
            new Section\SupervisordSection(array(
                'environment' => array(
                    'KEY' => 'value',
                    'fake_value'
                ),
            )),
            new Section\SupervisordSection(array(
                'loglevel' => 'warn',
            )),
            new Section\UnixHttpServerSection,
            new Section\RpcInterfaceSection('test'),
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
