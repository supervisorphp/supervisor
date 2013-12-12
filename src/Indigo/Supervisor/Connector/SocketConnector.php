<?php

namespace Indigo\Supervisor\Connector;

use Indigo\Supervisor\Exception\BadResourceException;

class SocketConnector extends AbstractConnector
{
    const CHUNK_SIZE = 8192;

    protected $socket;

    public function __construct($socket, $timeout = null)
    {
        $timeout = $timeout ?: ini_get("default_socket_timeout");

        $this->socket = fsockopen($socket, -1, $errNo, $errStr, $timeout);

        if ( ! is_resource($this->socket)) {
            throw new BadResourceException("Cannot open socket: " . $errStr, $errNo);
        }
    }

    public function __destruct()
    {
        fclose($this->socket);
    }

    public function call($namespace, $method, array $arguments = array())
    {
        $xml = xmlrpc_encode_request($namespace . '.' . $method, $arguments, array('encoding' => 'utf-8'));

        $request = "POST /RPC2 HTTP/1.1\r\n";

        foreach ($this->headers as $key => $value) {
            $request .= "$key: $value\r\n";
        }

        $request .= "Content-Length: " . strlen($xml) . "\r\n\r\n" . $xml;

        fwrite($this->socket, $request);

        $response = '';
        $header   = null;

        do {
            $response .= fread($this->socket, self::CHUNK_SIZE);

            if (is_null($header) and ($headerLength = strpos($response, "\r\n\r\n")) !== false) {
                $header = substr($response, 0, $headerLength);
                $header = explode("\r\n", $header);
                $header = array_slice($header, 1);

                foreach ($header as $key => $value) {
                    $value = explode(': ', $value);
                    $header[$value[0]] = $value[1];
                    unset($header[$key]);
                }

                if (array_key_exists('Content-Length', $header)) {
                    $contentLength = $header['Content-Length'];
                } else {
                    throw new ResponseException('No Content-Length field found in the HTTP header.');
                }
            }

            $bodyStartPos = $headerLength + 4;
            $bodyLength   = strlen($response) - $bodyStartPos;

        } while ($bodyLength < $contentLength);
        $response = substr($response, $bodyStartPos);

        return $this->processResponse($response);
    }
}
