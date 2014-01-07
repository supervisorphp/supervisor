<?php

namespace Indigo\Supervisor\Section;

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
            new EventListenerSection('test', array(
                'command' => 'cat /path/to/file'
            )),
            new FcgiProgramSection('test', array(
                'socket'  => '/path/to/socket',
                'command' => 'cat /path/to/file'
            )),
            new GroupSection('test', array(
                'programs' => array(
                    'test',
                    'empty'
                )
            )),
            new IncludeSection(array(
                'files' => array('/etc/supervisord/conf.d/*')
            )),
            new InetHttpServerSection(array(
                'port' => 9001
            )),
            new ProgramSection('test', array(
                'command' => 'cat /path/to/file',
                'environment' => array(
                    'KEY' => 'value',
                    'fake_value'
                )
            )),
            new SupervisorctlSection,
            new SupervisordSection(array(
                'environment' => array(
                    'KEY' => 'value',
                    'fake_value'
                )
            )),
            new UnixHttpServerSection,
        );
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
     * @depends testConfig
     */
    public function testRender($configuration)
    {
        $configuration->render();
    }
}