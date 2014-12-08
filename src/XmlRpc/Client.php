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

use Indigo\Http\Client as HttpClient;
use fXmlRpc\ClientInterface;
use fXmlRpc\Parser\ParserInterface;
use fXmlRpc\Parser\XmlReaderParser;
use fXmlRpc\Serializer\SerializerInterface;
use fXmlRpc\Serializer\XmlWriterSerializer;
use fXmlRpc\Exception\ResponseException;

final class Client implements ClientInterface
{
    /**
     * @var HttpClient
     */
    private $client;

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
     * If no specific transport, parser or serializer is passed, default implementations
     * are used.
     *
     * @param HttpClient          $transport
     * @param string              $uri
     * @param ParserInterface     $parser
     * @param SerializerInterface $serializer
     */
    public function __construct(
        HttpClient $client,
        $uri = null,
        ParserInterface $parser = null,
        SerializerInterface $serializer = null
    )
    {
        $this->client = $client;
        $this->parser = $parser ?: new XmlReaderParser();
        $this->serializer = $serializer ?: new XmlWriterSerializer();

        $client->setBaseUrl($uri);
    }

    /**
     * {@inheritdoc}
     */
    public function getUri()
    {
        return (string) $this->client->getBaseUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function setUri($uri)
    {
        $this->client->setBaseUrl($uri);
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

        $response = $this->parser->parse(
            $this->client->post(null, [
                'headers' => [
                    'Content-Type' => 'text/xml; charset=UTF-8',
                ],
                'body' => $this->serializer->serialize($methodName, $params)
            ]),
            $isFault
        );

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
