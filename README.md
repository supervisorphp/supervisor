# Indigo Supervisor

[![Build Status](https://img.shields.io/travis/indigophp/supervisor/develop.svg?style=flat-square)](https://travis-ci.org/indigophp/supervisor)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/indigophp/supervisor.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/supervisor)
[![Packagist Version](https://img.shields.io/packagist/v/indigophp/supervisor.svg?style=flat-square)](https://packagist.org/packages/indigophp/supervisor)
[![Total Downloads](https://img.shields.io/packagist/dt/indigophp/supervisor.svg?style=flat-square)](https://packagist.org/packages/indigophp/supervisor)
[![Quality Score](https://img.shields.io/scrutinizer/g/indigophp/supervisor.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/supervisor)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Dependency Status](https://www.versioneye.com/user/projects/53c28cef5a1b3479ca000b48/badge.svg?style=flat)](https://www.versioneye.com/user/projects/53c28cef5a1b3479ca000b48)

**PHP library for managing supervisord through XML-RPC API.**


## Install

Via Composer

``` json
{
    "require": {
        "indigophp/supervisor": "@stable"
    }
}
```


## Usage

``` php
use Indigo\Supervisor\Supervisor;
use Indigo\Supervisor\Process;

// Create new Connector
// See available connectors
$connector = ...;

$connector->setCredentials('user', '123');

$supervisor = new Supervisor($connector);

// returns Process object
$process->$supervisor->getProcess('test_process');

// returns array of process info
$supervisor->getProcessInfo('test_process');

// same as $supervisor->stopProcess($process);
// same as $supervisor->stopProcess('test_process');
$process->stop();

// Don't wait for process start, return immediately
$process->start('false');

// returns true if running
// same as $process->isState(Process::RUNNING);
$process->isRunning();

// returns process name
echo $process;

// returns process information
$process->getPayload();
```

**Currently available connectors:**

* [fXmlRpc](https://github.com/lstrojny/fxmlrpc)
* Zend XML-RPC

**Note:** fXmlRpc can be used with several HTTP Clients. See the list on it's website. This is the reason why Client specific connectors has been removed.


## Configuration

This section is about generating configuration file(s) for supervisord.

Example:

``` php
use Indigo\Supervisor\Configuration;
use Indigo\Supervisor\Section\Program;

$config = new Configuration;

$section = new SupervisordSection(array('identifier' => 'supervisor'));
$config->addSection($section);

$section = new ProgramSection('test', array('command' => 'cat'));
$config->addSection($section);

// same as echo $config->render()
echo $config;
```

The following sections are available in this pacakge:

* _Supervisord_
* _Supervisorctl_
* _UnixHttpServer_
* _InetHttpServer_
* _Include_
* _Group_*
* _Program_*
* _EventListener_*
* _FcgiProgram_*


***Note**: These sections has to be instantiated with a name and optionally an options array:
``` php
$section = new ProgramSection('test', array('command' => 'cat'));
```


### Existing configuration

You can parse your existing configuration, and use it as a `Configuration` object.

``` php
$configuration = new Configuration;

$configuration->parseFile('/etc/supervisor/supervisord.conf');

$ini = file_get_contents('/etc/supervisor/supervisord.conf');
$configuration->parseIni($ini);
```

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

If using PHP XML-RPC extension to parse responses (which is marked as *EXPERIMENTAL*). This can cause issues when you are trying to read/tail log of a PROCESS. Make sure you clean your log messages. The only information I found about this is a [comment](http://www.php.net/function.xmlrpc-decode#44213).

You will also have to make sure that you always call the functions with correct parameters. `ZendConnector` will trigger an error when incorrect parameters are passed. See [this](https://github.com/zendframework/zf2/issues/6455) issue for details. (Probably this won't change in near future based on my inspections of the code.) Other connectors will throw a `SupervisorException`.


## Bundles

Here is a list of framework specific bundle packages:

* [HumusSupervisorModule](https://github.com/prolic/HumusSupervisorModule) *(Zend Framework 2)*
* [Fuel Supervisor](https://github.com/indigophp/fuel-supervisor) *(FuelPHP 1.x)*


## Testing

``` bash
$ codecept run
```


## Contributing

Please see [CONTRIBUTING](https://github.com/indigophp/supervisor/blob/develop/CONTRIBUTING.md) for details.


## Credits

- [Márk Sági-Kazár](https://github.com/sagikazarmark)
- [All Contributors](https://github.com/indigophp/supervisor/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/indigophp/supervisor/blob/develop/LICENSE) for more information.
