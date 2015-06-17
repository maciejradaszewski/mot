Feature: Special Notices
  Admin users should be able to post special notices
  Users should be able to read any special notices sent to them

  Scenario: Broadcast a Special Notice
    Given I am logged in as a Special Notice broadcast user
    When I send a new Special Notice broadcast
    Then I will see the broadcast was successful

  Scenario: Area Office user creates new Special Notice
    Given I am logged in as a Scheme User
    When I create a Special Notice
    Then the Special Notice is created

  Scenario: Tester user attempts to create new Special Notice but does not have authorisation
    Given I am not logged in
    When I create a Special Notice
    Then the Special Notice is not created
