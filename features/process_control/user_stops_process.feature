Feature: User starts process
  In order to make some processes stopped
  As a User
  I should be able to stop them various ways

  Scenario:
    Given I have a process called "cat"
    And I have Supervisor running
    When I wait for start
    And I get information about the process before stopping it
    And I stop the process
    And I get information about the process
    Then I should see it running first
    Then I should get a success response for stopping it
    And I should see it not running

  Scenario:
    Given I have a process called "cat"
    And I have a process called "uname"
    And I have Supervisor running
    When I wait for start
    And I get information about the processes before stopping them
    And I stop the processes
    And I get information about the processes
    Then I should see them running first
    Then I should get a success response for stopping them
    And I should see them not running

  Scenario:
    Given I have a process called "cat"
    And it is part of group called "test"
    And I have a process called "uname"
    And it is part of group called "test"
    And I have Supervisor running
    When I wait for start
    And I get information about the processes before stopping them
    And I stop the processes in the group
    And I get information about the processes
    Then I should see them running first
    And I should see them as part of the group
    Then I should get a success response for stopping them
    And I should see them not running
