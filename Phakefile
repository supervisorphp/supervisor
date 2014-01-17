<?php

require __DIR__ . '/vendor/autoload.php';

use Indigo\Supervisor\Supervisor;
use Indigo\Supervisor\Connector\ConnectorInterface;
use Indigo\Supervisor\Connector\UnixSocketConnector;
use Indigo\Supervisor\Connector\InetSocketConnector;
use Indigo\Supervisor\EventListener\MemmonEventListener;

function auth(\ArrayAccess $app, ConnectorInterface $connector) {
    if (isset($app['user']) and isset($app['pass'])) {
        $connector->setCredentials($app['user'], $app['pass']);
    }
}

task('init', function ($app) {
    if (isset($app['unix'])) {
        $connector = new UnixSocketConnector($app['unix']);
    } elseif (isset($app['host'])) {
        $port = isset($app['port']) ? $app['port'] : 9001;
        $connector = new InetSocketConnector($app['host'], $port);
    } else {
        throw new \Exception('No connection data found');
    }

    auth($app, $connector);

    $app['supervisor'] = new Supervisor($connector);
});

task('memmon', 'init', function ($app) {
    $program = array();

    if (isset($app['program'])) {
        $programs = explode(',', $app['program']);

        foreach ($programs as $p) {
            $p = explode(':', $p);
            $program[$p[0]] = $p[1];
        }
    }

    $group = array();

    if (isset($app['group'])) {
        $groups = explode(',', $app['group']);

        foreach ($groups as $p) {
            $p = explode(':', $p);
            $group[$p[0]] = $p[1];
        }
    }

    $any = isset($app['any']) ? $app['any'] : 0;

    $listener = new MemmonEventListener($app['supervisor'], $program, $group, $any);

    $listener->listen();
});