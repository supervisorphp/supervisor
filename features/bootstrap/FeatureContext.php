<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Indigo\Supervisor\Configuration\Parser\File;
use Indigo\Supervisor\Configuration\Renderer\Basic as Renderer;
use Indigo\Supervisor\Connector\XmlRpc;
use Indigo\Supervisor\Supervisor;
use fXmlRpc\Client;
use fXmlRpc\Transport\Guzzle4Bridge;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\Process\Process;
use Liip\ProcessManager\ProcessManager;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeScenario
     */
    public function setUpSupervisor(BeforeScenarioScope $scope)
    {
        $parser = new File(__DIR__.'/../../resources/supervisord.conf');
        $this->configuration = $parser->parse();

        $client = new Client(
            'http://127.0.0.1:9001/RPC2',
            new Guzzle4Bridge(new GuzzleClient(['defaults' => ['auth' => ['user', '123']]]))
        );
        $connector = new XmlRpc($client);
        $this->supervisor = new Supervisor($connector);
    }

    /**
     * @AfterScenario
     */
    public function stopSupervisor(AfterScenarioScope $scope)
    {
        $this->processManager->killProcess($this->process);
    }

    /**
     * @Given I have Supervisor running
     */
    public function iHaveSupervisorRunning()
    {
        $renderer = new Renderer;
        $configuration = $renderer->render($this->configuration);
        $file = tempnam(sys_get_temp_dir(), 'supervisord_');
        file_put_contents($file, $configuration);

        $this->processManager = new ProcessManager;

        if ($this->supervisor->isConnected()) {
            $this->processManager->killProcess($this->supervisor->getPID());
        }

        $this->process = $this->processManager->execProcess(sprintf('supervisord --configuration %s', $file));

        $c = 0;
        while (!$this->supervisor->isConnected() and $c < 100) {
            usleep(10000);
            $c++;
        }

        if ($c >= 100) {
            throw new \RuntimeException('Could not connect to supervisord');
        }

        if ($this->process !== $this->supervisor->getPID()) {
            throw new \RuntimeException('Connected to supervisord with a different PID');
        }
    }

    /**
     * @When I ask for the API version
     */
    public function iAskForTheApiVersion()
    {
        $this->version = $this->supervisor->getAPIVersion();
    }

    /**
     * @Then I should get at least :ver version
     */
    public function iShouldGetAtLeastVersion($ver)
    {
        if (version_compare($this->version, $ver) == -1) {
            throw new \UnexpectedValueException(sprintf('Version "%s" does not match the minimum required "%s"', $this->version, $ver));
        }
    }

    /**
     * @When I ask for Supervisor version
     */
    public function iAskForSupervisorVersion()
    {
        $this->version = $this->supervisor->getVersion();
    }

    /**
     * @Given my Supervisor instance is called :identifier
     */
    public function mySupervisorInstanceIsCalled($identifier)
    {
        $supervisord = $this->configuration->getSection('supervisord');
        $supervisord->setProperty('identifier', $identifier);
    }

    /**
     * @When I ask for Supervisor identification
     */
    public function iAskForSupervisorIdentification()
    {
        $this->identifier = $this->supervisor->getIdentification();
    }

    /**
     * @Then I should get :identifier as identifier
     */
    public function iShouldGetAsIdentifier($identifier)
    {
        if ($this->identifier !== $identifier) {
            throw new \UnexpectedValueException(sprintf('Identification "%s" does not match the required "%s"', $this->identifier, $identifier));
        }
    }

    /**
     * @When I ask for the state
     */
    public function iAskForTheState()
    {
        $this->state = $this->supervisor->getState();
    }

    /**
     * @Then I should get :code as statecode and :name as statename
     */
    public function iShouldGetAsStatecodeAndAsStatename($code, $name)
    {
        if ($this->state['statecode'] != $code) {
            throw new \UnexpectedValueException(sprintf('State code "%s" does not match the required "%s"', $this->state['statecode'], $code));
        }

        if ($this->state['statename'] !== $name) {
            throw new \UnexpectedValueException(sprintf('Statename "%s" does not match the required "%s"', $this->state['statename'], $name));
        }
    }

    /**
     * @When I ask for the PID
     */
    public function iAskForThePid()
    {
        $this->pid = $this->supervisor->getPID();
    }

    /**
     * @Then I should get the real PID
     */
    public function iShouldGetTheRealPid()
    {
        if ($this->process !== $this->pid) {
            throw new \UnexpectedValueException(sprintf('PID "%s" does not match the real "%s"', $this->pid, $this->process));
        }
    }

    /**
     * @When I ask for the log
     */
    public function iAskForTheLog()
    {
        $this->log = trim($this->supervisor->readLog(-(35 + strlen($this->process)), 0));
    }

    /**
     * @Then I should get an INFO about supervisord started
     */
    public function iShouldGetAnInfoAboutSupervisordStarted()
    {
        if ($this->log !== 'INFO supervisord started with pid '.$this->process) {
            throw new \UnexpectedValueException(sprintf('The following log entry was expected: "%s", but we got this: "%s"', 'INFO supervisord started with pid '.$this->process, $this->log));
        }
    }

    /**
     * @When I try to clear the log
     */
    public function iTryToClearTheLog()
    {
        $this->cleared = $this->supervisor->clearLog();
    }

    /**
     * @When I check if it is really empty
     */
    public function iCheckIfItIsReallyEmpty()
    {
        $this->log = trim($this->supervisor->readLog(-24, 0));
    }

    /**
     * @Then I should get a success response for clearing
     */
    public function iShouldGetASuccessResponseForClearing()
    {
        if ($this->cleared !== true) {
            throw new \Exception('Clearing the log was unsuccessful');
        }
    }

    /**
     * @Then I should get a cleared log
     */
    public function iShouldGetAClearedLog()
    {
        if ($this->log !== 'INFO reopening log file') {
            throw new \Exception('Empty log cannot be confirmed');
        }
    }

    /**
     * @When I try to shut it down
     */
    public function iTryToShutItDown()
    {
        $this->shutdown = $this->supervisor->shutdown();
    }

    /**
     * @Then I should get a success response for shutting it down
     */
    public function iShouldGetASuccessResponseForShuttingItDown()
    {
        if ($this->shutdown !== true) {
            throw new \Exception('Shutdown was unsuccessful');
        }
    }

    /**
     * @Then it should be stopped
     */
    public function itShouldBeStopped()
    {
        if ($this->supervisor->isConnected() === true) {
            throw new \Exception('Supervisor is still available');
        }
    }

    /**
     * @When I try to restart it
     */
    public function iTryToRestartIt()
    {
        $this->restart = $this->supervisor->restart();
    }

    /**
     * @Then I should get a success response for restarting it
     */
    public function iShouldGetASuccessResponseForRestartingIt()
    {
        if ($this->restart !== true) {
            throw new \Exception('Restart was unsuccessful');
        }
    }

    /**
     * @Then it should be running again
     */
    public function itShouldBeRunningAgain()
    {
        if ($this->supervisor->isConnected() !== true) {
            throw new \Exception('Supervisor is unavailable');
        }
    }
}
