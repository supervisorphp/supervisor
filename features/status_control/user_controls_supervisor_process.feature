Feature: User controls Supervisor process
  In order to be able to manage Supervisor
  As a User
  I should be able to control the process itself

  Scenario:
    Given I have Supervisor running
    When I try to shut it down
    Then I should get a success response for shutting it down
    And it should be stopped

  Scenario:
    Given I have Supervisor running
    When I try to restart it
    Then I should get a success response for restarting it
    And it should be running again
