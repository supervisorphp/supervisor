# Supervisor

[![Latest Version](https://img.shields.io/github/release/supervisorphp/supervisor.svg?style=flat-square)](https://github.com/supervisorphp/supervisor/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Test Suite](https://github.com/supervisorphp/supervisor/workflows/Test%20Suite/badge.svg?event=push)](https://github.com/supervisorphp/supervisor/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/supervisorphp/supervisor.svg?style=flat-square)](https://packagist.org/packages/supervisorphp/supervisor)

**PHP library for managing Supervisor through XML-RPC API.**

## Install

Via Composer

```bash
composer require supervisorphp/supervisor
```

## Usage

This library depends on the fast and powerful [fXmlRpc](https://github.com/lstrojny/fxmlrpc) library, which supports a number of adapters to use your preferred HTTP client to make connections.

In the example below, we will use the popular Guzzle HTTP client library.

This example requires some additional libraries to function. To include the necessary extra components, you can run:

```bash
composer require guzzlehttp/guzzle:^7.0
```

This example shows how to pass authentication credentials to Guzzle, initiate the fXmlRpc client, and pass that to SupervisorPHP.

```php
// Create Guzzle HTTP client
$guzzleClient = new \GuzzleHttp\Client([
    'auth' => ['user', '123'],
]);

// Pass the url and the guzzle client to the fXmlRpc Client
$client = new fXmlRpc\Client(
    'http://127.0.0.1:9001/RPC2',
    new fXmlRpc\Transport\PsrTransport(
        new GuzzleHttp\Psr7\HttpFactory(),
        $guzzleClient
    )
);

// Or, if connecting via a Unix Domain Socket
$guzzleClient = new \GuzzleHttp\Client([
    'curl' => [
        \CURLOPT_UNIX_SOCKET_PATH => '/var/run/supervisor.sock',
    ],
]);

$client = new fXmlRpc\Client(
    'http://localhost/RPC2',
    new fXmlRpc\Transport\PsrTransport(
        new GuzzleHttp\Psr7\HttpFactory(),
        $guzzleClient
    )
);

// Pass the client to the Supervisor library.
$supervisor = new Supervisor\Supervisor($client);

// returns Process object
$process = $supervisor->getProcess('test_process');

// returns array of process info
$supervisor->getProcessInfo('test_process');

// same as $supervisor->stopProcess($process);
$supervisor->stopProcess('test_process');

// Don't wait for process start, return immediately
$supervisor->startProcess($process, false);

// returns true if running
// same as $process->checkState(Process::RUNNING);
$process->isRunning();

// returns process name
echo $process;

// returns process information
$process->getPayload();
```

### Exception handling

For each possible fault response there is an exception. These exceptions extend a [common exception](src/Exception/Fault.php), so you are able to catch a specific fault or all. When an unknown fault is returned from the server, an instance if the common exception is thrown. The list of fault responses and the appropriate exception can be found in the class.

```php
/** @var \Supervisor\Supervisor $supervisor */

try {
	$supervisor->startProcess('process', true);
} catch (\Supervisor\Exception\Fault\BadNameException $e) {
	// handle bad name error here
} catch (\Supervisor\Exception\SupervisorException $e) {
	// handle any other errors here
}
```

## Configuration and Event listening

[Configuration](https://github.com/supervisorphp/configuration) and [Event](https://github.com/supervisorphp/event) components have been moved into their own repository.

## Further info

You can find the Supervisor XML-RPC documentation here:
[http://supervisord.org/api.html](http://supervisord.org/api.html)

## Notice

If you use PHP XML-RPC extension to parse responses (which is marked as *EXPERIMENTAL*). This can cause issues when you are trying to read/tail log of a PROCESS. Make sure you clean your log messages. The only information I found about this is a [comment](http://www.php.net/function.xmlrpc-decode#44213).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Testing

```bash
$ composer test
```

Functional tests (behat):

```bash
$ behat
```

### Docker Image

This repository ships with a Docker Compose configuration and a Dockerfile for easy testing. Tests can be run via:

```bash
docker-compose run --rm ci
```

## Deprecated Libraries

While this tries to be a complete Supervisor client, this isn't the first one. However some authors decided to deprecate their packages in favor of this:

- [Supervisord PHP Client](https://github.com/mondalaci/supervisord-php-client)
- [Indigo Supervisor](https://github.com/indigophp/supervisor)

## Credits

- [László Monda](https://github.com/mondalaci) (author of Supervisord PHP Client)
- [Márk Sági-Kazár](https://github.com/sagikazarmark)
- [All Contributors](https://github.com/supervisorphp/supervisor/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
