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
            array(new EventListenerSection('test', array(
                'command' => 'cat /path/to/file'
            ))),
            array(new FcgiProgramSection('test', array(
                'socket'  => '/path/to/socket',
                'command' => 'cat /path/to/file'
            ))),
            array(new GroupSection('test', array(
                'programs' => array(
                    'test',
                    'empty'
                )
            ))),
            array(new IncludeSection(array(
                'files' => array('/etc/supervisord/conf.d/*')
            ))),
            array(new InetHttpServerSection(array(
                'port' => 9001
            ))),
            array(new ProgramSection('test', array(
                'command' => 'cat /path/to/file'
            ))),
            array(new SupervisorctlSection),
            array(new SupervisordSection),
            array(new UnixHttpServerSection),
        );
    }

    /**
     * @dataProvider provider
     */
    public function testConfiguration($section)
    {
        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Configuration',
            $this->configuration->addSection($section)
        );
    }

    public function testSection()
    {
        foreach ($this->provider() as $section) {
            $section = reset($section);

            $this->assertInstanceOf(
                'Indigo\\Supervisor\\Section\\SectionInterface',
                $section
            );
        }
    }
}