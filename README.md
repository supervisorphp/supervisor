# Indigo Supervisor

[![Latest Version](https://img.shields.io/github/release/indigophp/supervisor.svg?style=flat-square)](https://github.com/indigophp/supervisor/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/indigophp/supervisor/develop.svg?style=flat-square)](https://travis-ci.org/indigophp/supervisor)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/indigophp/supervisor.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/supervisor)
[![Quality Score](https://img.shields.io/scrutinizer/g/indigophp/supervisor.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/supervisor)
[![HHVM Status](https://img.shields.io/hhvm/indigophp/supervisor.svg?style=flat-square)](http://hhvm.h4cc.de/package/indigophp/supervisor)
[![Total Downloads](https://img.shields.io/packagist/dt/indigophp/supervisor.svg?style=flat-square)](https://packagist.org/packages/indigophp/supervisor)
[![Dependency Status](https://www.versioneye.com/user/projects/53c28cef5a1b3479ca000b48/badge.svg?style=flat)](https://www.versioneye.com/user/projects/53c28cef5a1b3479ca000b48)

**PHP library for managing supervisord through XML-RPC API.**


## Install

Via Composer

``` bash
$ composer require indigophp/supervisor
```


## Usage

``` php
use Indigo\Supervisor\Supervisor;
use Indigo\Supervisor\Connector\XmlRpc;
use Indigo\Supervisor\XmlRpc\Client;
use Indigo\Supervisor\XmlRpc\Authentication;

// Pass an instance of Indigo\Http\Adapter as the first argument
// Optional: if you provid your HTTP Client with authentication data
// then you can use directly it's adapter without this decorator
$authentication = new Authentication($adapter, 'user', '123');

// Pass the url and the adapter to the XmlRpc Client
$client = new Client('http://127.0.0.1:9001/RPC2', $authentication);

// Pass the client to the connector
// See the full list of connectors bellow
$connector = new XmlRpc($client);

$supervisor = new Supervisor($connector);

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

**Currently available connectors:**

* [fXmlRpc](https://github.com/lstrojny/fxmlrpc)
* Zend XML-RPC

**Note:** fXmlRpc can be used with several HTTP Clients. See the list on it's website. This is the reason why Client specific connectors has been removed. There is also a custom Client implementing `fXmlRpc\ClientInterface` which uses [indigophp/http-adapter](https://github.com/indigophp/http-adapter) package.

### Authentication

As of version 3.0.0 `setCredentials` is no longer part of the `Connector` interface. As in the example you can use the `Authentication` adapter, but that only works if you use [indigophp/http-adapter](https://github.com/indigophp/http-adapter) adapters. Otherwise you have to provide authentication data to the HTTP Client of your choice. (For example Guzzle supports it out-of-the-box)


## Configuration

This section is about generating configuration file(s) for supervisord.

``` php
use Indigo\Supervisor\Configuration;
use Indigo\Supervisor\Configuration\Section\Supervisord;
use Indigo\Supervisor\Configuration\Section\Program;
use Indigo\Supervisor\Configuration\Renderer\Basic;

$config = new Configuration;

$section = new Supervisord(['identifier' => 'supervisor']);
$config->addSection($section);

$section = new Program('test', ['command' => 'cat']);
$config->addSection($section);

echo $renderer->render($config);
```

The following sections are available in this pacakge:

- _Supervisord_
- _Supervisorctl_
- _UnixHttpServer_
- _InetHttpServer_
- _Includes_**
- _Group_*
- _Program_*
- _EventListener_*
- _FcgiProgram_*


***Note**: These sections has to be instantiated with a name and optionally a properties array:

``` php
$section = new Program('test', ['command' => 'cat']);
```

****Note:** The keyword `include` is reserved in PHP, so the class name is `Includes`, but the section name is still `include`.


### Existing configuration

You can parse your existing configuration, and use it as a `Configuration` object.

``` php
use Indigo\Supervisor\Configuration;
use Indigo\Supervisor\Configuration\Parser\File;

$parser = new File('/etc/supervisor/supervisord.conf');

$configuration = new Configuration;

// argument is optional, returns a new Configuration object if not passed
$parser->parse($configuration);
```

Available parsers:

- _File_
- _Text_


You can find detailed info about options for each section here:
[http://supervisord.org/configuration.html](http://supervisord.org/configuration.html)


## Event Listeners

Supervisor has this pretty good feature: notify you(r listener) about it's events.

The main entry point is the `Processor`. `Processor`s handle the connection between the event handling and the Supervisor instance. There are two implemented `Processor`s, however you can implement your own for more features (for example a `LoggerProcessor` to log all the exchanged messages).

Th package uses [league/event](http://event.thephpleague.com) for event handling. `Processor`s need an instance of `EventEmitter` which you can register your listeners in.


``` php
use Indigo\Supervisor\Event\StandardProcessor;
use League\Event\EventEmitter;

$emitter = new EventEmitter;

// it is important to set the result of event
$emitter->addListener('TICK_5', function($event) {
    $event->setResult(StandardProcessor::OK);
});

// processor using standard input
$processor = new StandardProcessor($emitter);

// start the processor
$processor->run();
```

Check the Supervisor docs for more about [Events](http://supervisord.org/events.htm).


## Further info

You can find the XML-RPC documentation here:
[http://supervisord.org/api.html](http://supervisord.org/api.html)


## Notice

If you use PHP XML-RPC extension to parse responses (which is marked as *EXPERIMENTAL*). This can cause issues when you are trying to read/tail log of a PROCESS. Make sure you clean your log messages. The only information I found about this is a [comment](http://www.php.net/function.xmlrpc-decode#44213).

You will also have to make sure that you always call the functions with correct parameters. `Zend` connector will trigger an error when incorrect parameters are passed. See [this](https://github.com/zendframework/zf2/issues/6455) issue for details. (Probably this won't change in near future based on my inspections of the code.) Other connectors will throw a `SupervisorException`.


## Bundles

Here is a list of framework specific bundle packages:

* [HumusSupervisorModule](https://github.com/prolic/HumusSupervisorModule) *(Zend Framework 2)*
* [Fuel Supervisor](https://github.com/indigophp/fuel-supervisor) *(FuelPHP 1.x)*


## Testing

``` bash
$ phpspec run
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Márk Sági-Kazár](https://github.com/sagikazarmark)
- [All Contributors](https://github.com/indigophp/supervisor/contributors)


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
