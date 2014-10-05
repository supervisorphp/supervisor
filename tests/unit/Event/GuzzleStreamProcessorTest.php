<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Event;

use GuzzleHttp\Stream\Utils;

/**
 * Tests for StandardProcessor
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Event\GuzzleStreamProcessor
 * @group              Supervisor
 * @group              Event
 */
class GuzzleStreamProcessorTest extends AbstractProcessorTest
{
    public function _before()
    {
        parent::_before();

        $input = Utils::create('');
        $output = Utils::create('');
        $emitter = $this->getEmitterMock();

        $this->processor = new GuzzleStreamProcessor($input, $output, $emitter);
    }

    /**
     * {@inheritdoc}
     */
    protected function write($data)
    {
        $stream = $this->processor->getInputStream();

        $stream->write($data);
        $stream->seek(0);
    }

    /**
     * {@inheritdoc}
     */
    protected function readLine()
    {
        return trim(Utils::readLine($this->processor->getOutputStream()));
    }

    /**
     * {@inheritdoc}
     */
    protected function rewindOutput()
    {
        $this->processor->getOutputStream()->seek(0);
    }

    /**
     * @covers ::getInputStream
     * @covers ::setInputStream
     * @covers ::getOutputStream
     * @covers ::setOutputStream
     */
    public function testStreams()
    {
        $input = $this->processor->getInputStream();

        $this->assertInstanceOf('GuzzleHttp\\Stream\\StreamInterface', $input);
        $this->assertSame($this->processor, $this->processor->setInputStream($input));

        $output = $this->processor->getOutputStream();

        $this->assertInstanceOf('GuzzleHttp\\Stream\\StreamInterface', $output);
        $this->assertSame($this->processor, $this->processor->setOutputStream($output));
    }
}
