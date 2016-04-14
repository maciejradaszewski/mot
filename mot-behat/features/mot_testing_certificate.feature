
Feature: Mot Testing Certificate
  As a user I should be able to enter certificate details

  Scenario Outline: As a user without DVSA roles I should be able to enter certificate details on own profile
    Given I am logged in as <user>
    And I have "<group_a_status>" status for group "A"
    And I have "<group_b_status>" status for group "B"
    When I enter mot testing certificate details
    Then Mot Testing Certificate details for class "A" is created
    And Mot Testing Certificate details for class "B" is created
    And Qualification status for group "A" is set to "DEMO_TEST_NEEDED"
    And Qualification status for group "B" is set to "DEMO_TEST_NEEDED"
    Examples:
      | user       | group_a_status          | group_b_status          |
      | a new User | INITIAL_TRAINING_NEEDED | INITIAL_TRAINING_NEEDED |
      | a new User | INITIAL_TRAINING_NEEDED | NOT_APPLIED             |
      | a new User | NOT_APPLIED             | INITIAL_TRAINING_NEEDED |
      | a new User | NOT_APPLIED             | NOT_APPLIED             |
      | a Tester   | INITIAL_TRAINING_NEEDED | INITIAL_TRAINING_NEEDED |
      | a Tester   | INITIAL_TRAINING_NEEDED | NOT_APPLIED             |
      | a Tester   | NOT_APPLIED             | INITIAL_TRAINING_NEEDED |

  Scenario Outline: As a DVSA user I should be able to enter certificate details on other user profile
    Given I am logged in as <user>
    And person with "<group_a_status>" status for group "A" has account
    And person with "<group_b_status>" status for group "B" has account
    When I enter mot testing certificate details for person
    Then Mot Testing Certificate details for class "A" is created
    And Mot Testing Certificate details for class "B" is created
    And Qualification status for group "A" is set to "DEMO_TEST_NEEDED"
    And Qualification status for group "B" is set to "DEMO_TEST_NEEDED"
    Examples:
      | user                 | group_a_status          | group_b_status          |
      | a Vehicle Examiner   | INITIAL_TRAINING_NEEDED | INITIAL_TRAINING_NEEDED |
      | a Area Office 1      | INITIAL_TRAINING_NEEDED | NOT_APPLIED             |
      | a Area Office User 2 | NOT_APPLIED             | INITIAL_TRAINING_NEEDED |
      | a Scheme Manager     | NOT_APPLIED             | NOT_APPLIED             |
      | a Scheme User        | INITIAL_TRAINING_NEEDED | INITIAL_TRAINING_NEEDED |

  Scenario Outline: As a applicant tester I should be able to remove certificate details on own profile
    Given I am logged in as <user>
    And I have Mot Testing Certificate for group "A"
    And I have Mot Testing Certificate for group "B"
    When I remove Mot Testing Certificate for group "A"
    And Qualification status for group "A" is set to "NOT_APPLIED"
    And Qualification status for group "B" is set to "DEMO_TEST_NEEDED"
    Examples:
      | user       |
      | a new User |
      | a Tester   |

  Scenario Outline: As a DVSA user I should be able to remove certificate details on other user profile
    Given I am logged in as <user>
    And person with Mot Testing Certificate for group "A" exists
    And person with Mot Testing Certificate for group "B" exists
    When I remove Mot Testing Certificate for group "A"
    And Qualification status for group "A" is set to "NOT_APPLIED"
    And Qualification status for group "B" is set to "DEMO_TEST_NEEDED"
    Examples:
      | user                 |
      | a Vehicle Examiner   |
      | a Area Office 1      |
      | a Area Office User 2 |
      | a Scheme Manager     |
      | a Scheme User        |

