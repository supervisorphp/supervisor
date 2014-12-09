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

        $this->taskCleanDir([__DIR__.'/src/Exception/Fault'])->run();

        foreach ($faults as $code => $name) {
            $exName = $this->createExceptionName($name);
            $file = sprintf(__DIR__.'/src/Exception/Fault/%s.php', $exName);

            $this->taskWriteToFile($file)
                ->textFromFile(__DIR__.'/resources/FaultTemplate.php')
                ->place('FAULT_NAME', $name)
                ->place('FaultName', $exName)
                ->run();
        }
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
