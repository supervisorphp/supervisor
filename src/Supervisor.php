<?php

namespace Supervisor;

use fXmlRpc\ClientInterface;
use fXmlRpc\Exception\FaultException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Supervisor\Exception\SupervisorException;

/**
 * Class Supervisor
 * @package Supervisor
 *
 * @link http://supervisord.org/api.html
 *
 * @method string getAPIVersion()
 * @method string getSupervisorVersion()
 * @method string getIdentification()
 * @method array getState()
 * @method int getPID()
 * @method string readLog(integer $offset, integer $limit)
 * @method bool clearLog()
 * @method bool shutdown()
 * @method bool restart()
 * @method array getProcessInfo(string $processName)
 * @method array getAllProcessInfo()
 * @method bool startProcess(string $name, boolean $wait = true)
 * @method bool startAllProcesses(boolean $wait = true)
 * @method bool startProcessGroup(string $name, boolean $wait = true)
 * @method bool stopProcess(string $name, boolean $wait = true)
 * @method bool stopAllProcesses(boolean $wait = true)
 * @method bool stopProcessGroup(string $name, boolean $wait = true)
 * @method bool sendProcessStdin(string $name, string $chars)
 * @method bool addProcessGroup(string $name)
 * @method bool removeProcessGroup(string $name)
 * @method string readProcessStdoutLog(string $name, integer $offset, integer $limit)
 * @method string readProcessStderrLog(string $name, integer $offset, integer $limit)
 * @method string tailProcessStdoutLog(string $name, integer $offset, integer $limit)
 * @method string tailProcessStderrLog(string $name, integer $offset, integer $limit)
 * @method bool clearProcessLogs(string $name)
 * @method bool clearAllProcessLogs()
 * @method array reloadConfig()
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 * @author Buster Neece <buster@busterneece.com>
 */
final class Supervisor implements SupervisorInterface
{
    private ClientInterface $client;

    private LoggerInterface $logger;

    public function __construct(ClientInterface $client, ?LoggerInterface $logger = null)
    {
        $this->client = $client;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @inheritDoc
     */
    public function call(string $namespace, string $method, array $arguments = [])
    {
        try {
            $this->logger->debug(
                sprintf('Supervisor call to "%s"', $namespace . '.' . $method),
                $arguments
            );

            return $this->client->call($namespace . '.' . $method, $arguments);
        } catch (FaultException $faultException) {
            $this->logger->error(
                sprintf('Supervisor fault: ' . $faultException->getMessage()),
                [
                    'faultString' => $faultException->getFaultString(),
                    'faultCode' => $faultException->getFaultCode(),
                ]
            );

            throw SupervisorException::create($faultException);
        }
    }

    /**
     * @inheritDoc
     */
    public function __call(string $method, array $arguments)
    {
        return $this->call('supervisor', $method, $arguments);
    }

    /**
     * @inheritDoc
     */
    public function isConnected(): bool
    {
        try {
            $this->call('system', 'listMethods');
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function isRunning(): bool
    {
        return $this->checkState(self::RUNNING);
    }

    /**
     * @inheritDoc
     */
    public function checkState(int $checkState): bool
    {
        $state = $this->getState();

        return $state['statecode'] === $checkState;
    }

    /**
     * @inheritDoc
     */
    public function getAllProcesses(): array
    {
        $processes = $this->getAllProcessInfo();

        foreach ($processes as $key => $processInfo) {
            $processes[$key] = new Process($processInfo);
        }

        return $processes;
    }

    /**
     * @inheritDoc
     */
    public function getProcess(string $name): ProcessInterface
    {
        $process = $this->getProcessInfo($name);

        return new Process($process);
    }
}
