Feature: User starts process
  In order to make some processes running
  As a User
  I should be able to start them various ways

  Scenario:
    Given I have a process called "cat"
    And autostart is disabled
    And I have Supervisor running
    When I get information about the process before starting it
    And I start the process
    And I get information about the process
    Then I should see it not running first
    Then I should get a success response for starting it
    And I should see it running

  Scenario:
    Given I have a process called "cat"
    And autostart is disabled
    And I have a process called "ls"
    And autostart is disabled
    And I have Supervisor running
    When I get information about the processes before starting them
    And I start the processes
    And I get information about the processes
    Then I should see them not running first
    Then I should get a success response for starting them
    And I should see them running

  Scenario:
    Given I have a process called "cat"
    And autostart is disabled
    And it is part of group called "test"
    And I have a process called "ls"
    And autostart is disabled
    And it is part of group called "test"
    And I have Supervisor running
    When I get information about the processes before starting them
    And I start the processes in the group
    And I get information about the processes
    Then I should see them not running first
    But I should see them as part of the group
    Then I should get a success response for starting them
    And I should see them running
