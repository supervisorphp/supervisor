<?php

namespace Buzz\Client;

use Codeception\TestCase\Test;

/**
 * Tests for Stream client
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Buzz\Client\Stream
 */
class StreamTest extends Test
{
    protected $client;

    public function _before()
    {
        $this->client = Stream::create(fopen(__DIR__.'/../../resources/response', 'r'));
    }

    public function testStream()
    {
        
    }
}
