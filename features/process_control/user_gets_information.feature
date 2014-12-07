Feature: User gets information
  In order to know what processes are running
  As a User
  I should be able to get information about them

  Scenario:
    Given I have a process called "cat"
    And I have Supervisor running
    When I wait for start
    And I get information about the process
    Then I should see it running

  Scenario:
    Given I have a process called "cat"
    And I have a process called "ls"
    And I have Supervisor running
    When I wait for start
    And I get information about the processes
    Then I should see them running
