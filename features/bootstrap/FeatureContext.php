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
use Indigo\Supervisor\Configuration\Section;
use Indigo\Supervisor\Connector\XmlRpc;
use Indigo\Supervisor\Supervisor;
use fXmlRpc\Client;
use fXmlRpc\Transport\Guzzle4Bridge;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\Process\Process;

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
    public function __construct($bin = 'supervisord')
    {
        $this->bin = $bin;
    }

    /**
     * @BeforeScenario
     */
    public function setUpSupervisor(BeforeScenarioScope $scope)
    {
        $parser = new File(__DIR__.'/../../resources/supervisord.conf');
        $this->configuration = $parser->parse();

        $this->setUpConnector();
    }

    protected function setUpConnector()
    {
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
        isset($this->process) and posix_kill($this->process, SIGKILL);
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

        if ($this->supervisor->isConnected()) {
            posix_kill($this->supervisor->getPID(), SIGKILL);
        }

        $command = sprintf('(%s --configuration %s > /dev/null 2>&1 & echo $!)&', $this->bin, $file);
        exec($command, $op);
        $this->process = (int)$op[0];

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
            throw new \Exception('Restarting Supervisor was unsuccessful');
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

    /**
     * @Given I have a process called :process
     */
    public function iHaveAProcessCalled($process)
    {
        $this->processName = $process;

        $program = new Section\Program($process, [
            'command' => '/bin/'.$process,
        ]);

        $this->configuration->addSection($program);
    }

    /**
     * @When I wait for start
     */
    public function iWaitForStart()
    {
        usleep(100000);
    }

    /**
     * @When I get information about the process
     */
    public function iGetInformationAboutTheProcess()
    {
        $this->processInfo = $this->supervisor->getProcessInfo($this->processName);
    }

    /**
     * @Then I should see it running
     */
    public function iShouldSeeItRunning()
    {
        if ($this->processInfo['state'] < 10) {
            throw new \Exception(sprintf('Process %s failed to start', $this->processInfo['name']));
        }
    }

    /**
     * @When I get information about the processes
     */
    public function iGetInformationAboutTheProcesses()
    {
        $this->processInfo = $this->supervisor->getAllProcessInfo();
    }

    /**
     * @Then I should see them running
     */
    public function iShouldSeeThemRunning()
    {
        foreach ($this->processInfo as $process) {
            if ($process['state'] < 10) {
                throw new \Exception(sprintf('Process %s failed to start', $process['name']));
            }
        }
    }

    /**
     * @Given autostart is disabled
     */
    public function autostartIsDisabled()
    {
        $program = $this->configuration->getSection('program:'.$this->processName);

        $program->setProperty('autostart', false);
    }

    /**
     * @When I get information about the process before starting it
     */
    public function iGetInformationAboutTheProcessBeforeStartingIt()
    {
        $this->firstProcessInfo = $this->supervisor->getProcessInfo($this->processName);
    }

    /**
     * @When I start the process
     */
    public function iStartTheProcess()
    {
        $this->start = $this->supervisor->startProcess($this->processName, false);
    }

    /**
     * @Then I should see it not running first
     */
    public function iShouldSeeItNotRunningFirst()
    {
        if ($this->firstProcessInfo['state'] > 0) {
            throw new \Exception(sprintf('Process %s is already running before start', $this->firstProcessInfo['name']));
        }
    }

    /**
     * @Then I should get a success response for starting it
     */
    public function iShouldGetASuccessResponseForStartingIt()
    {
        if ($this->start !== true) {
            throw new \Exception(sprintf('Starting process "%s" was unsuccessful', $this->processName));
        }
    }

    /**
     * @When I get information about the processes before starting them
     */
    public function iGetInformationAboutTheProcessesBeforeStartingThem()
    {
        $this->firstProcessInfo = $this->supervisor->getAllProcessInfo();
    }

    /**
     * @When I start the processes
     */
    public function iStartTheProcesses()
    {
        $this->start = $this->supervisor->startAllProcesses(false);
    }

    /**
     * @Then I should see them not running first
     */
    public function iShouldSeeThemNotRunningFirst()
    {
        foreach ($this->firstProcessInfo as $process) {
            if ($process['state'] > 0) {
                throw new \Exception(sprintf('Process %s is already running before start', $process['name']));
            }
        }
    }

    /**
     * @Then I should get a success response for starting them
     */
    public function iShouldGetASuccessResponseForStartingThem()
    {
        foreach ($this->start as $start) {
            if ($start['description'] !== 'OK') {
                throw new \Exception(sprintf('Starting process "%s" was unsuccessful', $start['name']));
            }
        }
    }

    /**
     * @Given it is part of group called :grp
     */
    public function itIsPartOfGroupCalled($grp)
    {
        $this->groupName = $grp;

        $program = $this->configuration->getSection('program:'.$this->processName);
        $group = $this->configuration->getSection('group:'.$grp);

        if (is_null($group)) {
            $group = new Section\Group($grp, ['programs' => $this->processName]);
            $this->configuration->addSection($group);
        } else {
            $programs = $group->getProperty('programs');
            $programs[] = $this->processName;
            $group->setProperty('programs', $programs);
        }
    }

    /**
     * @When I start the processes in the group
     */
    public function iStartTheProcessesInTheGroup()
    {
        $this->groupResponse = $this->start = $this->supervisor->startProcessGroup($this->groupName, false);
    }

    /**
     * @Then I should see them as part of the group
     */
    public function iShouldSeeThemAsPartOfTheGroup()
    {
        foreach ($this->groupResponse as $groupResponse) {
            if ($groupResponse['group'] !== $this->groupName) {
                throw new \Exception(sprintf('Process "%s" is not part of the group "%s"', $groupResponse['name'], $this->groupName));
            }
        }
    }

    /**
     * @When I get information about the process before stopping it
     */
    public function iGetInformationAboutTheProcessBeforeStoppingIt()
    {
        $this->firstProcessInfo = $this->supervisor->getProcessInfo($this->processName);
    }

    /**
     * @When I stop the process
     */
    public function iStopTheProcess()
    {
        $this->stop = $this->supervisor->stopProcess($this->processName, false);
    }

    /**
     * @Then I should see it running first
     */
    public function iShouldSeeItRunningFirst()
    {
        if ($this->firstProcessInfo['state'] < 10) {
            throw new \Exception(sprintf('Process %s is not running before stop', $this->firstProcessInfo['name']));
        }
    }

    /**
     * @Then I should get a success response for stopping it
     */
    public function iShouldGetASuccessResponseForStoppingIt()
    {
        if ($this->stop !== true) {
            throw new \Exception(sprintf('Stopping process "%s" was unsuccessful', $this->processName));
        }
    }

    /**
     * @Then I should see it not running
     */
    public function iShouldSeeItNotRunning()
    {
        if ($this->processInfo['state'] > 0) {
            throw new \Exception(sprintf('Process %s failed to stop', $this->processInfo['name']));
        }
    }

    /**
     * @When I get information about the processes before stopping them
     */
    public function iGetInformationAboutTheProcessesBeforeStoppingThem()
    {
        $this->firstProcessInfo = $this->supervisor->getAllProcessInfo();
    }

    /**
     * @When I stop the processes
     */
    public function iStopTheProcesses()
    {
        $this->stop = $this->supervisor->stopAllProcesses(false);
    }

    /**
     * @Then I should see them running first
     */
    public function iShouldSeeThemRunningFirst()
    {
        foreach ($this->firstProcessInfo as $process) {
            if ($process['state'] < 10) {
                throw new \Exception(sprintf('Process %s is not running before stop', $process['name']));
            }
        }
    }

    /**
     * @Then I should get a success response for stopping them
     */
    public function iShouldGetASuccessResponseForStoppingThem()
    {
        foreach ($this->stop as $stop) {
            if ($stop['description'] !== 'OK') {
                throw new \Exception(sprintf('Stopping process "%s" was unsuccessful', $stop['name']));
            }
        }
    }

    /**
     * @Then I should see them not running
     */
    public function iShouldSeeThemNotRunning()
    {
        foreach ($this->processInfo as $process) {
            if ($process['state'] > 0) {
                throw new \Exception(sprintf('Process %s failed to stop', $process['name']));
            }
        }
    }

    /**
     * @When I stop the processes in the group
     */
    public function iStopTheProcessesInTheGroup()
    {
        $this->groupResponse = $this->stop = $this->supervisor->stopProcessGroup($this->groupName, false);
    }
}
