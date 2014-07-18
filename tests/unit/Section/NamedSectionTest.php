<?php

namespace Test\Unit;

use Indigo\Supervisor\Section\DummyNamedSection;
use Codeception\TestCase\Test;

/**
 * Tests for Named Sections
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Section\AbstractNamedSection
 */
class NamedSectionTest extends Test
{
    protected $section;

    public function setUp()
    {
        $this->section = new DummyNamedSection('name');
    }

    /**
     * @covers ::__construct
     * @group  Supervisor
     */
    public function testConstruct()
    {
        $section = new DummyNamedSection('names', array('optional' => 1));

        $this->assertEquals(array('optional' => 1), $section->getOptions());
        $this->assertEquals('dummy:names', $section->getName());
    }

    /**
     * @covers ::getName
     * @group  Supervisor
     */
    public function testName()
    {
        $this->assertEquals('dummy:name', $this->section->getName());
    }
}
