<?php
// Here you can initialize variables that will for your tests

require_once __DIR__.'/stubs/DummyConnector.php';
require_once __DIR__.'/stubs/DummySection.php';
require_once __DIR__.'/stubs/DummyXmlrpcConnector.php';

$GLOBALS['host'] = 'localhost:9001';
$GLOBALS['username'] = 'user';
$GLOBALS['password'] = 123;
$GLOBALS['socket'] = '/var/run/supervisor.sock';
