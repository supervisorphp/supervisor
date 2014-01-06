# Indigo Supervisor

[![Build Status](https://travis-ci.org/indigophp/supervisor.png?branch=develop)](https://travis-ci.org/indigophp/supervisor)

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


## Usage

``` php
use Indigo\Supervisor\Supervisor;
use Indigo\Supervisor\Process;

$connector = new Indigo\Supervisor\Connector\InetConnector('localhost', 9001);
//$connector = new Indigo\Supervisor\Connector\SocketConnector('unix:///var/run/supervisor.lock');

$connector->setCredentials('user', '123');

$supervisor = new Indigo\Supervisor\Supervisor($connector);

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


***Note**: These sections has to instantiated with a name and optionally an options array:
```php
$section = new ProgramSection('test', array('command' => 'cat'));
```

You can find detailed info about options for each section here:
[http://supervisord.org/configuration.html](http://supervisord.org/configuration.html)


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