<?php

namespace Supervisor;

use fXmlRpc\ClientInterface;
use fXmlRpc\Exception\FaultException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Supervisor\Exception\SupervisorException;

/**
 * @link http://supervisord.org/api.html
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 * @author Buster Neece <buster@busterneece.com>
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
 * @method array startAllProcesses(boolean $wait = true)
 * @method array startProcessGroup(string $name, boolean $wait = true)
 * @method bool stopProcess(string $name, boolean $wait = true)
 * @method array stopAllProcesses(boolean $wait = true)
 * @method array stopProcessGroup(string $name, boolean $wait = true)
 * @method bool sendProcessStdin(string $name, string $chars)
 * @method bool addProcessGroup(string $name)
 * @method bool removeProcessGroup(string $name)
 * @method string readProcessStdoutLog(string $name, integer $offset, integer $limit)
 * @method string readProcessStderrLog(string $name, integer $offset, integer $limit)
 * @method array tailProcessStdoutLog(string $name, integer $offset, integer $limit)
 * @method array tailProcessStderrLog(string $name, integer $offset, integer $limit)
 * @method bool clearProcessLogs(string $name)
 * @method array clearAllProcessLogs()
 * @method array reloadConfig()
 * @method bool signalProcess(string $name, string $signal)
 * @method array signalProcessGroup(string $name, string $signal)
 * @method array signalAllProcesses(string $signal)
 */
final class Supervisor implements SupervisorInterface
{
    private readonly LoggerInterface $logger;

    public function __construct(
        private readonly ClientInterface $client,
        ?LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @inheritDoc
     */
    public function call(string $namespace, string $method, array $arguments = []): mixed
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
        return $this->checkState(ServiceStates::Running);
    }

    /**
     * @inheritDoc
     */
    public function getServiceState(): ServiceStates
    {
        return ServiceStates::from($this->getState()['statecode']);
    }

    /**
     * @inheritDoc
     */
    public function checkState(int|ServiceStates $checkState): bool
    {
        if (is_int($checkState)) {
            $checkState = ServiceStates::tryFrom($checkState);
        }

        return $this->getServiceState() === $checkState;
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
