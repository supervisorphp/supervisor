Feature: Admin controls Supervisor process
    In order to be able to manage Supervisor
    As an Admin
    I should be able to control the process itself

    Scenario:
        Given I have Supervisor running
        When I try to call "shutdown" action
        Then I should get a success response
        And it should be stopped

    Scenario:
        Given I have Supervisor running
        When I try to call "restart" action
        And I wait for start
        Then I should get a success response
        And it should be running again
