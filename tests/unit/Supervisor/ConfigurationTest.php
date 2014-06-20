<?php

namespace Indigo\Supervisor;

use Codeception\TestCase\Test;
use Indigo\Supervisor\Section\SectionInterface;

/**
 * Tests for Configuration
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Configuration
 */
class ConfigurationTest extends Test
{
    protected $config;

    public function _before()
    {
        $this->config = new Configuration;
    }

    /**
     * @covers ::addSectionMap
     * @group  Supervisor
     */
    public function testSectionMap()
    {
        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Configuration',
            $this->config->addSectionMap('supervisor', 'Fake\\Supervisor')
        );
    }

    /**
     * @covers ::reset
     * @group  Supervisor
     */
    public function testReset()
    {
        $section = \Mockery::mock('Indigo\\Supervisor\\Section\\SectionInterface', function ($mock) {
            $mock->shouldReceive('getName')->andReturn('test');
            $mock->shouldReceive('getOptions')->andReturn(array('test' => true));
        });

        $this->config->addSection($section);

        $this->assertEquals(
            array($section->getName() => $section),
            $this->config->reset()
        );
    }

    /**
     * @covers ::addSection
     * @covers ::getSection
     * @group  Supervisor
     */
    public function testSection()
    {
        $section = \Mockery::mock('Indigo\\Supervisor\\Section\\SectionInterface', function ($mock) {
            $mock->shouldReceive('getName')->andReturn('test');
            $mock->shouldReceive('getOptions')->andReturn(array('test' => true));
        });

        $emptySection = \Mockery::mock('Indigo\\Supervisor\\Section\\SectionInterface', function ($mock) {
            $mock->shouldReceive('getName')->andReturn('empty');
            $mock->shouldReceive('getOptions')->andReturn(false);
        });

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Configuration',
            $this->config->addSection($section)
        );

        $this->assertInstanceOf(
            get_class($section),
            $this->config->getSection('test')
        );

        $this->assertNull($this->config->getSection('nope'));

        $this->assertContains(
            $section,
            $this->config->getSection()
        );
    }

        /**
     * @covers ::addSection
     * @covers ::removeSection
     * @group  Supervisor
     */
    public function testRemoveSection()
    {
        $fakeSection = \Mockery::mock('Indigo\\Supervisor\\Section\\SectionInterface', function ($mock) {
            $mock->shouldReceive('getName')->andReturn('fake');
        });

        $this->config->addSection($fakeSection);

        $this->assertTrue($this->config->removeSection('fake'));
    }

        /**
     * @covers ::removeSection
     * @group  Supervisor
     */
    public function testRemoveFakeSection()
    {
        $this->assertFalse($this->config->removeSection('fake'));
    }

    /**
     * @covers ::render
     * @covers ::__toString
     * @group  Supervisor
     */
    public function testRender()
    {
        $section = \Mockery::mock('Indigo\\Supervisor\\Section\\SectionInterface', function ($mock) {
            $mock->shouldReceive('getName')->andReturn('test');
            $mock->shouldReceive('getOptions')->andReturn(array('test' => true));
        });

        $emptySection = \Mockery::mock('Indigo\\Supervisor\\Section\\SectionInterface', function ($mock) {
            $mock->shouldReceive('getName')->andReturn('empty');
            $mock->shouldReceive('getOptions')->andReturn(false);
        });

        $this->config->addSection($section);
        $this->config->addSection($emptySection);

        $render = $this->config->render();

        $this->assertEquals($render, (string) $this->config);
    }

    /**
     * @covers ::parseFile
     * @covers ::parseIni
     * @covers ::parseIniSection
     * @group  Supervisor
     */
    public function testParseFile()
    {
        $this->config->parseFile(__DIR__ . '/../../../resources/supervisord.conf');

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Section\\SupervisordSection',
            $this->config->getSection('supervisord')
        );
    }

    /**
     * @covers ::parseString
     * @covers ::parseIni
     * @covers ::parseIniSection
     * @group  Supervisor
     */
    public function testParseString()
    {
        $string = @file_get_contents(__DIR__ . '/../../../resources/supervisord.conf');
        $this->config->parseString($string);

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Section\\SupervisordSection',
            $this->config->getSection('supervisord')
        );
    }

    /**
     * @covers            ::parseString
     * @covers            ::parseIni
     * @covers            ::parseIniSection
     * @expectedException UnexpectedValueException
     * @group             Supervisor
     */
    public function testParseFailure()
    {
        $string = "[fake_section]
option = fake";

        $this->config->parseString($string);
    }
}
