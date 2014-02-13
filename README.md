# Indigo Supervisor

[![Build Status](https://travis-ci.org/indigophp/supervisor.png?branch=develop)](https://travis-ci.org/indigophp/supervisor)
[![Code Coverage](https://scrutinizer-ci.com/g/indigophp/supervisor/badges/coverage.png?s=fb01dfd7a7c8f4b08e4aba045631b1f1bb02dec3)](https://scrutinizer-ci.com/g/indigophp/supervisor/)
[![Latest Stable Version](https://poser.pugx.org/indigophp/supervisor/v/stable.png)](https://packagist.org/packages/indigophp/supervisor)
[![Total Downloads](https://poser.pugx.org/indigophp/supervisor/downloads.png)](https://packagist.org/packages/indigophp/supervisor)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/indigophp/supervisor/badges/quality-score.png?s=6aaa222466e706bbb6417ba4906c544d72741cbe)](https://scrutinizer-ci.com/g/indigophp/supervisor/)

**PHP library for managing supervisord through XML-RPC**


## Install

Via Composer

``` json
{
    "require": {
        "indigophp/supervisor": "dev-master"
    }
}
```

**Note**: Package now uses PSR-4 autoloader, make sure you have a fresh version of Composer.


## Usage

``` php
use Indigo\Supervisor\Supervisor;
use Indigo\Supervisor\Process;
use Indigo\Supervisor\Connector;

$connector = new Connector\InetConnector('localhost', 9001);
//$connector = new Connector\SocketConnector('unix:///var/run/supervisor.lock');

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


## Configuration

This section is about generating configuration file(s) for supervisord.

Example:

``` php
use Indigo\Supervisor\Configuration;
use Indigo\Supervisor\Section\ProgramSection;

$config = new Configuration();

$section = new SupervisordSection(array('identifier' => 'supervisor'));
$config->addSection($section);

$section = new ProgramSection('test', array('command' => 'cat'));
$config->addSection($section);

// same as echo $config->render()
echo $config;
```

The following sections are available in this pacakge:

* *SupervisordSection*
* *SupervisorctlSection*
* *UnixHttpServerSection*
* *InetHttpServerSection*
* *IncludeSection*
* *GroupSection**
* *ProgramSection**
* *EventListenerSection**
* *FcgiProgramSection**


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


## CLI Usage

You can use CLI commands to manage your Supervisor instance and Processes. If you want to use this feature you need to include [symfony/console](https://github.com/symfony/console) into "require" key in your own composer.json.

For full list of commands run:

``` bash
supervisor list
```


## Event Listeners

Supervisor has this pretty good feature: notify you(r listener) about it's events, so it was obivious to implement this.

It is important that this is only the logic of event processing. Making it work is your task. You have to create a console application which calls the `EventDispatcher`. You also have to create your own listeners, however, there are some included. Check the Supervisor docs for more about [Events](http://supervisord.org/events.htm).


``` php
use Indigo\Supervisor;
use Indigo\Supervisor\EventListener;

// this is an example listener for development purposes
$listener = new NullEventListener();

// optional
$listener->setLogger(new \Psr\Log\NullLogger());

// start listening
$listener->listen();
```

You may have noticed that I used PSR-3 LoggerInterface. By default, the included listeners use a NullLogger, so you don't need to add a logger instance to it, but you can if you want. In your listeners it's your job whether you want to use logging or not, but `setLogger` is already implemented in `AbstractEventListener`.


### Writting an EventListener

There are three ways to write an event listener:
* By implementing `EventListenerInterface` and writting the whole logic on your own
* By extending `AbstractEventListener` and writting only the event process logic
* By using `EventListenerTrait` and writting only the event process logic

An example if you chose one of the las two points:

``` php
protected function doListen (array $payload)
{
    // Checking event name in header
    if ($payload[0]['eventname'] !== 'TICK_5') {
        // Invalid event, but we want to continue running the listener itself
        return true;
    }

    // Do some logic
    $process = $payload[1]['process_name'];
    $body = isset($payload[2]) ? $payload[2] : null;

    if ($process == 'kill_me') {
        exec('kill -9 ' . $payload[1]['pid']);
        return 0;
    } elseif ($process == 'stop_listener') {
        // Stop listener
        return 2;
    }
}
```

**Note**: Exit code 2 does not have the meaning exit. Anything else than 0 and 1 (success and failure) means exit now. This may change in the future.


## Further info

You can find the XML-RPC documentation here:
[http://supervisord.org/api.html](http://supervisord.org/api.html)


## Notice

All the responses are parsed by PHP XML-RPC extension (which is marked as *EXPERIMENTAL*). This can cause issues when you are trying to read/tail log of a PROCESS. Make sure you clean your log messages. The only information I found about this is a [comment](http://www.php.net/function.xmlrpc-decode#44213).


## Testing

``` bash
$ phpunit
```


## Contributing

Please see [CONTRIBUTING](https://github.com/indigophp/supervisor/blob/develop/CONTRIBUTING.md) for details.


## Credits

- [Márk Sági-Kazár](https://github.com/sagikazarmark)
- [All Contributors](https://github.com/indigophp/supervisor/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/indigophp/supervisor/blob/develop/LICENSE) for more information.