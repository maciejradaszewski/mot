Feature: Temp Password Change
  As a Tester
  I want to force a password change when logging in
  So that I can have a temporary password sent via post

  Scenario: Temporary Password Change
    Given I am logged in as a Tester with a Temp Password
    When I update my password
    Then I will no longer be prompted for password change
