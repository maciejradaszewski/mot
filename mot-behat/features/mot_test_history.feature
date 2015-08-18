Feature: MOT Test history
  As an authenticated DVSA
  I want to be able to search mot tests
  So that I can see mot test history

  Scenario Outline: Search for MOT tests by username
    Given I am logged in as <user_role>
    And "2" MOT tests have been created by different testers with the same prefix
    When I search for an MOT tests by username
    Then the MOT test history is returned
    Examples:
      | user_role          |
      | a Vehicle Examiner |
      | an Area Office User|

  Scenario Outline: Search for MOT tests by partial username
    Given I am logged in as <user_role>
    And "2" MOT tests have been created by different testers with the same prefix
    When I search for an MOT tests by partial username
    Then the MOT test history is not returned
    Examples:
      | user_role          |
      | a Vehicle Examiner |
      | an Area Office User|