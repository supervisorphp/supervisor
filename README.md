# Supervisor

[![Latest Version](https://img.shields.io/github/release/supervisorphp/supervisor.svg?style=flat-square)](https://github.com/supervisorphp/supervisor/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/supervisorphp/supervisor.svg?style=flat-square)](https://travis-ci.org/supervisorphp/supervisor)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/supervisorphp/supervisor.svg?style=flat-square)](https://scrutinizer-ci.com/g/supervisorphp/supervisor)
[![Quality Score](https://img.shields.io/scrutinizer/g/supervisorphp/supervisor.svg?style=flat-square)](https://scrutinizer-ci.com/g/supervisorphp/supervisor)
[![HHVM Status](https://img.shields.io/hhvm/supervisorphp/supervisor.svg?style=flat-square)](http://hhvm.h4cc.de/package/supervisorphp/supervisor)
[![Total Downloads](https://img.shields.io/packagist/dt/supervisorphp/supervisor.svg?style=flat-square)](https://packagist.org/packages/supervisorphp/supervisor)
[![Dependency Status](https://img.shields.io/versioneye/d/php/supervisorphp:supervisor.svg?style=flat-square)](https://www.versioneye.com/php/supervisorphp:supervisor)

[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/supervisorphp/supervisor?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

**PHP library for managing Supervisor through XML-RPC API.**


## Install

Via Composer

``` bash
$ composer require supervisorphp/supervisor
```


## Usage

``` php
use Supervisor\Supervisor;
use fXmlRpc\Client;

// Pass the url to the XmlRpc Client
$client = new Client('http://127.0.0.1:9001/RPC2');

$supervisor = new Supervisor($client);

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

As of 4.0.0 Connectors are replaced by one client implementation: [fXmlRpc](https://github.com/lstrojny/fxmlrpc). The reason behind this change is that in the future this client will be the only one supported (Zend and HTTP Client specific connectors are deprecated) and it has a simple interface which can be used to create custom "connectors"/clients.


### Authentication

As of version 3.0.0 `setCredentials` is no longer part of the `Connector` interface (meaning responsibility has been fully removed).You have to provide authentication data to the HTTP Client of your choice. (For example Guzzle supports it out-of-the-box) Also, Bridges implemented by fXmlRpc supports to set custom headers.


### Exception handling

For each possible fault response there is an exception. These exceptions extend a [common exception](src/Exception/Fault.php), so you are able to catch a specific fault or all. When an unknown fault is returned from the server, an instance if the common exception is thrown. The list of fault responses and the appropriate exception can be found in the class.

``` php
use Supervisor\Exception\Fault;
use Supervisor\Exception\Fault\BadName;

try {
	$supervisor->restart('process');
} catch (BadName $e) {
	// handle bad name error here
} catch (Fault $e) {
	// handle any other errors here
}
```

**For developers:** Fault exceptions are automatically generated, there is no need to manually modify them.


## Configuration and Event listening

[Configuration](https://github.com/supervisorphp/configuration) and [Event](https://github.com/supervisorphp/event) components have been moved into their own repository.


## Further info

You can find the XML-RPC documentation here:
[http://supervisord.org/api.html](http://supervisord.org/api.html)


## Notice

If you use PHP XML-RPC extension to parse responses (which is marked as *EXPERIMENTAL*). This can cause issues when you are trying to read/tail log of a PROCESS. Make sure you clean your log messages. The only information I found about this is a [comment](http://www.php.net/function.xmlrpc-decode#44213).


## Testing

``` bash
$ phpspec run
```

Functional tests (behat):

``` bash
$ behat
```


## Vagrant

There is a `Vagrantfile` provided in this repo which you can use to run functional tests without installing Supervisor on your local machine. It installs the latest version from PyPi, but the library itself is tested against 3.0, which is the lowest officially supported Supervisor version.


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Deprecated libraries

While this tries to a be a complete Supervisor client, this isn't the first one. However some authors decided to deprecate their packages in favor of this:

- [Supervisord PHP Client](https://github.com/mondalaci/supervisord-php-client)
- [Indigo Supervisor](https://github.com/indigophp/supervisor)


## Credits

- [László Monda](https://github.com/mondalaci) (author of Supervisord PHP Client)
- [Márk Sági-Kazár](https://github.com/sagikazarmark)
- [All Contributors](https://github.com/supervisorphp/supervisor/contributors)


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
