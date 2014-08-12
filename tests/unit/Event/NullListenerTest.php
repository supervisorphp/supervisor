<?php

namespace Test\Unit;

use Indigo\Supervisor\Event\NullListener;

/**
 * Tests for Null Listener
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Event\NullListener
 * @group              Supervisor
 * @group              Listener
 */
class NullListenerTest extends AbstractListenerTest
{
    public function _before()
    {
        $this->listener = new NullListener;
    }

    /**
     * @covers ::doListen
     */
    public function testInstance()
    {
        $event = \Mockery::mock('Indigo\\Supervisor\\Event\\EventInterface');

        $this->assertEquals(0, $this->listener->doListen($event));
    }
}
