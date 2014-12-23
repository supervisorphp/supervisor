<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\XmlRpc;

use Indigo\Http\Adapter;
use Indigo\Http\Stream;
use Indigo\Http\Message\Request;
use fXmlRpc\ClientInterface;
use fXmlRpc\Parser\ParserInterface;
use fXmlRpc\Parser\XmlReaderParser;
use fXmlRpc\Serializer\SerializerInterface;
use fXmlRpc\Serializer\XmlWriterSerializer;
use fXmlRpc\Exception\ResponseException;
use fXmlRpc\Multicall;

final class Client implements ClientInterface
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var array
     */
    private $prependParams = [];

    /**
     * @var array
     */
    private $appendParams = [];

    /**
     * If no specific parser or serializer is passed, default implementations
     * are used.
     *
     * @param string              $uri
     * @param Adapter             $adapter
     * @param ParserInterface     $parser
     * @param SerializerInterface $serializer
     */
    public function __construct(
        $uri,
        Adapter $adapter,
        ParserInterface $parser = null,
        SerializerInterface $serializer = null
    )
    {
        $this->uri = $uri;
        $this->adapter = $adapter;
        $this->parser = $parser ?: new XmlReaderParser;
        $this->serializer = $serializer ?: new XmlWriterSerializer;
    }

    /**
     * {@inheritdoc}
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * {@inheritdoc}
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function prependParams(array $params)
    {
        $this->prependParams = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrependParams()
    {
        return $this->prependParams;
    }

    /**
     * {@inheritdoc}
     */
    public function appendParams(array $params)
    {
        $this->appendParams = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function getAppendParams()
    {
        return $this->appendParams;
    }

    /**
     * {@inheritdoc}
     */
    public function call($methodName, array $params = [])
    {
        $params = array_merge($this->prependParams, $params, $this->appendParams);

        $request = new Request;
        $body = Stream::create($this->serializer->serialize($methodName, $params));

        $request->setHeader('Content-Type', 'text/xml; charset=UTF-8');
        $request->setBody($body);
        $request->setMethod(Request::POST);
        $request->setUrl($this->uri);

        $response = $this->adapter->send($request);

        $response = $this->parser->parse($response->getBody()->getContents(), $isFault);

        if ($isFault) {
            throw ResponseException::fault($response);
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function multicall()
    {
        return new Multicall($this);
    }
}
