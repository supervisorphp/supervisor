<?php

namespace Test\Unit;

use Codeception\TestCase\Test;
use Indigo\Supervisor\Section\DummySection;

/**
 * Tests for Sections
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Section\AbstractSection
 */
class SectionTest extends Test
{
    protected $section;

    public function setUp()
    {
        $this->section = new DummySection;
    }

    /**
     * @covers ::__construct
     * @group  Supervisor
     */
    public function testConstruct()
    {
        $section = new DummySection(array('optional' => 1));

        $this->assertEquals(array('optional' => 1), $section->getOptions());
    }

    /**
     * @covers ::getName
     * @group  Supervisor
     */
    public function testName()
    {
        $this->assertEquals('dummy', $this->section->getName());
    }

    /**
     * @covers ::getOptions
     * @covers ::setOptions
     * @covers ::hasOptions
     * @group  Supervisor
     */
    public function testOptions()
    {
        $this->assertFalse($this->section->hasOptions());
        $this->assertSame($this->section, $this->section->setOptions(array('optional' => 2)));
        $this->assertEquals(array('optional' => 2), $this->section->getOptions());
        $this->assertTrue($this->section->hasOptions());
    }
}
