<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\Unit;

use Indigo\Supervisor\Section\DummyNamedSection;
use Codeception\TestCase\Test;

/**
 * Tests for Named Sections
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Section\AbstractNamedSection
 * @group              Supervisor
 * @group              Section
 */
class NamedSectionTest extends Test
{
    protected $section;

    public function _before()
    {
        $this->section = new DummyNamedSection('name');
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $section = new DummyNamedSection('names', array('optional' => 1));

        $this->assertEquals(array('optional' => 1), $section->getOptions());
        $this->assertEquals('dummy:names', $section->getName());
    }

    /**
     * @covers ::getName
     */
    public function testName()
    {
        $this->assertEquals('dummy:name', $this->section->getName());
    }
}
