Feature: Record Demo test outcome
  As a authenticated DVSA
  I want to be able to see when a user's Tester Qualification Status changes
  So that I can track the event changes

  @story @VM-10521 @VM-10520
  Scenario Outline: VE or AO1 updates user’s Tester Qualification status
    Given I am logged in as <user_role>
    When I change a user's group "<group>" tester qualification status from "Demo Test Needed" to Qualified
    Then a status change event is generated for the user of "<Event Type>"
    And the user will receive a status change notification for group "<group>"
  Examples:
    | user_role     | group | Event Type                   |
    | a VM10519User | A     | Group A Tester Qualification |
    | a VM10519User | B     | Group B Tester Qualification |


  @story @VM-10519
  Scenario Outline: VE or AO1 updates user’s Tester Qualification status different than Qualified
    Given I am logged in as <user_role>
    When I change a user's group "<group>" tester qualification status from "<status>" to Qualified
    Then an error occurs
    And a status change event is NOT generated for the user of "<Event Type>"
    And the user will NOT receive a status change notification
  Examples:
    | user_role     | group | status           | Event Type                   |
    | a VM10519User | A     | Refresher Needed | Group A Tester Qualification |
    | a VM10519User | B     | Qualified        | Group B Tester Qualification |