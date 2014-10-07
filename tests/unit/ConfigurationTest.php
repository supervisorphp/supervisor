<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor;

use Codeception\TestCase\Test;

/**
 * Tests for Configuration
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Configuration
 * @group              Supervisor
 * @group              Main
 */
class ConfigurationTest extends Test
{
    /**
     * Configuration object
     *
     * @var Configuration
     */
    protected $config;

    /**
     * Section mock
     *
     * @var Section
     */
    protected $section;

    public function _before()
    {
        $this->config = new Configuration;

        $this->section = \Mockery::mock('Indigo\\Supervisor\\Section');

        $this->section->shouldReceive('getName')->andReturn('test')->byDefault();
        $this->section->shouldReceive('getOptions')->andReturn(['test' => true])->byDefault();
        $this->section->shouldReceive('hasOptions')->andReturn(true)->byDefault();
    }

    /**
     * @covers ::addSectionMap
     */
    public function testSectionMap()
    {
        $this->assertSame(
            $this->config,
            $this->config->addSectionMap('supervisor', 'Fake\\Supervisor')
        );
    }

    /**
     * @covers ::reset
     */
    public function testReset()
    {
        $this->config->addSection($this->section);

        $this->assertEquals(
            $this->config->getSections(),
            $this->config->reset()
        );
    }

    /**
     * @covers ::getSection
     * @covers ::getSections
     * @covers ::hasSection
     * @covers ::addSection
     * @covers ::addSections
     */
    public function testSection()
    {
        $this->assertFalse($this->config->hasSection('test'));

        $this->assertSame(
            $this->config,
            $this->config->addSection($this->section)
        );

        $this->assertSame(
            $this->config,
            $this->config->addSections([$this->section])
        );

        $this->assertTrue($this->config->hasSection('test'));

        $this->assertSame(
            $this->section,
            $this->config->getSection('test')
        );

        $this->assertNull($this->config->getSection('nope'));

        $this->assertContains(
            $this->section,
            $this->config->getSections()
        );
    }

        /**
     * @covers ::addSection
     * @covers ::removeSection
     */
    public function testRemoveSection()
    {
        $this->section->shouldReceive('getName')->andReturn('fake');

        $this->config->addSection($this->section);

        $this->assertTrue($this->config->removeSection('fake'));
    }

        /**
     * @covers ::removeSection
     */
    public function testRemoveFakeSection()
    {
        $this->assertFalse($this->config->removeSection('fake'));
    }

    /**
     * @covers ::render
     * @covers ::__toString
     */
    public function testRender()
    {
        $this->config->addSection($this->section);

        $render = $this->config->render();

        $this->assertEquals($render, (string) $this->config);
    }

    /**
     * @covers ::renderSection
     */
    public function testRenderSection()
    {
        $this->config->addSection($this->section);

        $render1 = $this->config->renderSection($this->section);
        $render2 = $this->config->render();

        $this->assertEquals($render1, $render2);
    }

    /**
     * @covers ::parseFile
     * @covers ::parseIni
     * @covers ::parseIniSection
     */
    public function testParseFile()
    {
        $this->config->parseFile(__DIR__ . '/../../resources/supervisord.conf');

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Section\\SupervisordSection',
            $this->config->getSection('supervisord')
        );
    }

    /**
     * @covers ::parseString
     * @covers ::parseIni
     * @covers ::parseIniSection
     */
    public function testParseString()
    {
        $string = @file_get_contents(__DIR__ . '/../../resources/supervisord.conf');
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
     */
    public function testParseFailure()
    {
        $string = "[fake_section]
option = fake";

        $this->config->parseString($string);
    }
}
