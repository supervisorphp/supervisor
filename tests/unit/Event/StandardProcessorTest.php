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
 * @coversDefaultClass Indigo\Supervisor\Event\StandardProcessor
 * @group              Supervisor
 * @group              Event
 */
class StandardProcessorTest extends AbstractProcessorTest
{
    public function _before()
    {
        parent::_before();

        $this->processor = new StandardProcessor($this->getEmitterMock());

        $this->processor->setInputStream(Utils::open('php://temp', 'r+'));
        $this->processor->setOutputStream(Utils::open('php://temp', 'w+'));
    }

    /**
     * {@inheritdoc}
     */
    protected function write($data)
    {
        $length = strlen($data);
        $stream = $this->processor->getInputStream();

        @fwrite($stream, $data);
        @fseek($stream, -$length, SEEK_CUR);
    }

    /**
     * {@inheritdoc}
     */
    protected function readLine()
    {
        return trim(fgets($this->processor->getOutputStream()));
    }

    /**
     * {@inheritdoc}
     */
    protected function rewindOutput()
    {
        rewind($this->processor->getOutputStream());
    }

    /**
     * @covers ::getInputStream
     * @covers ::setInputStream
     * @covers ::getOutputStream
     * @covers ::setOutputStream
     * @covers ::assertValidStreamResource
     */
    public function testStreams()
    {
        $input = $this->processor->getInputStream();

        $this->assertInternalType('resource', $input);
        $this->assertSame($this->processor, $this->processor->setInputStream($input));

        $output = $this->processor->getOutputStream();

        $this->assertInternalType('resource', $output);
        $this->assertSame($this->processor, $this->processor->setOutputStream($output));
    }

    /**
     * @covers            ::setInputStream
     * @covers            ::assertValidStreamResource
     * @expectedException InvalidArgumentException
     */
    public function testInvalidInputStream()
    {
        $this->processor->setInputStream('invalid_stream');
    }

    /**
     * @covers            ::setOutputStream
     * @covers            ::assertValidStreamResource
     * @expectedException InvalidArgumentException
     */
    public function testInvalidOutputStream()
    {
        $this->processor->setOutputStream('invalid_stream');
    }
}
