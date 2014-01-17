<?php

namespace Indigo\Supervisor;

use Indigo\Supervisor\Section\SectionInterface;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    protected $config;

    public function setUp()
    {
        $this->config = new Configuration;
    }

    public function tearDown()
    {
        \Mockery::mock();
    }

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

    public function testRemoveSection()
    {
        $fakeSection = \Mockery::mock('Indigo\\Supervisor\\Section\\SectionInterface', function ($mock) {
            $mock->shouldReceive('getName')->andReturn('fake');
        });

        $this->config->addSection($fakeSection);

        $this->assertTrue($this->config->removeSection('fake'));
    }

    public function testRemoveFakeSection($value='')
    {
        $this->assertFalse($this->config->removeSection('fake'));
    }

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

        $this->assertEquals($render, (string)$this->config);
    }

    public function testParseFile()
    {
        $this->config->reset();
        $this->config->parseFile(__DIR__ . '/../../supervisord.conf');

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Section\\SupervisordSection',
            $this->config->getSection('supervisord')
        );
    }

    public function testParseString()
    {
        $this->config->reset();

        $string = @file_get_contents(__DIR__ . '/../../supervisord.conf');
        $this->config->parseString($string);

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Section\\SupervisordSection',
            $this->config->getSection('supervisord')
        );
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testParseFailure()
    {
        $this->config->reset();

        $string = "[fake_section]
option = fake";

        $this->config->parseString($string);
    }
}
