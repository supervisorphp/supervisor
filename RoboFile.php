<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    /**
     * Generates fault exception classes
     */
    public function faultsGenerate()
    {
        $faults = include __DIR__.'/resources/faults.php';
        $maxStringLength = max(array_map('strlen', $faults));
        $maxCodeLength = max(array_map('strlen', array_keys($faults)));

        $this->taskCleanDir([__DIR__.'/src/Exception/Fault'])->run();
        $fault = $this->taskWriteToFile(__DIR__.'/src/Exception/Fault.php')->textFromFile(__DIR__.'/resources/Fault.php');
        $constants = $exceptionMap = [];

        foreach ($faults as $code => $name) {
            $exName = $this->createExceptionName($name);
            $file = sprintf(__DIR__.'/src/Exception/Fault/%s.php', $exName);
            $constants[] = sprintf('    const %s = %d', $name.str_repeat(' ', $maxStringLength - strlen($name)), $code);
            $exceptionMap[] = sprintf('        %s => \'Indigo\Supervisor\Exception\Fault\%s\',', $code.str_repeat(' ', $maxCodeLength - strlen($code)), $exName);

            $this->taskWriteToFile($file)
                ->textFromFile(__DIR__.'/resources/FaultTemplate.php')
                ->place('FAULT_NAME', $name)
                ->place('FaultName', $exName)
                ->run();
        }

        $fault->place('CONSTANTS', implode("\n", $constants));
        $fault->place('EXCEPTION_MAP', implode("\n", $exceptionMap));
        $fault->run();
    }

    /**
     * Returns a CamelCased exception name from UNDER_SCORED fault string
     *
     * @param string $faultString
     *
     * @return string
     */
    protected function createExceptionName($faultString)
    {
        $parts = explode('_', $faultString);

        $parts = array_map(function($el) { return ucfirst(strtolower($el)); }, $parts);

        return implode('', $parts);
    }
}
