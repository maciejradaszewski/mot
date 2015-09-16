Feature: Password
  As a User
  I want to change password when logging in
  So that I can log in with new password

  Scenario Outline: Password Change Successfully
    Given I am logged in as <user>
    When user fills in "<oldPassword>", "<newPassword>", "<confirmNewPassword>"
    Then my password is updated
    And I can log in with new password "<newPassword>"
  Examples:
    | user               | oldPassword | newPassword  | confirmNewPassword |
    | a Tester           | Password1   | NewPassword1 | NewPassword1       |
    | a Vehicle Examiner | Password1   | NewPassword1 | NewPassword1       |

  Scenario Outline: Password Change Unsuccessfully
    Given I am logged in as <user>
    When user fills in "<oldPassword>", "<newPassword>", "<confirmNewPassword>"
    Then my password is not updated
  Examples:
    | user               | oldPassword     | newPassword  | confirmNewPassword |
    | a Tester           | Password1       | NewPassword1 | NewPassword2       |
    | a Vehicle Examiner | Password1       | NewPassword1 | NewPassword2       |
    | a Tester           | InvalidPassword | NewPassword1 | NewPassword1       |
    | a Vehicle Examiner | InvalidPassword | NewPassword1 | NewPassword1       |
    | a Tester           | Password1       | NewPassword1 |                    |
    | a Vehicle Examiner | Password1       | NewPassword1 |                    |