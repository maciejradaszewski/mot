Feature: Special Notices
  Admin users should be able to post special notices
  Users should be able to read any special notices sent to them

  @special-notice
  Scenario: Broadcast a Special Notice
    Given I am logged in as a Special Notice broadcast user
    When I send a new Special Notice broadcast
    Then I will see the broadcast was successful

  @quarantine
  @special-notice
  Scenario Outline: Scheme User user creates new internal Special Notice
    Given I am logged in as a Scheme User
    And site with dvsa and vts users roles exists
    And I create a Special Notice with data:
      | targetRoles    | internalPublishDate   | externalPublishDate |
      | <target_roles> | <internal_date>       | <external_date>     |
    And I publish Special Notice
    When the Special Notice is broadcasted
    Then users received Special Notice
    Examples:
      | target_roles              | internal_date   | external_date   |
      | DVSA, VTS                 | now             | tomorrow        |
      | DVSA, VTS                 | tomorrow        | tomorrow        |
      | DVSA, TESTER-CLASS-1      | now             | tomorrow        |
      | DVSA, TESTER-CLASS-1      | tomorrow        | tomorrow        |
      | DVSA                      | now             | tomorrow        |
      | DVSA, VTS, TESTER-CLASS-1 | now             | now             |
      | VTS, TESTER-CLASS-1       | tomorrow        | now             |
      | VTS, TESTER-CLASS-1       | tomorrow        | tomorrow        |
      | VTS, TESTER-CLASS-2       | tomorrow        | now             |
      | VTS, TESTER-CLASS-2       | tomorrow        | tomorrow        |

  @quarantine
  @special-notice
  Scenario: Tester user attempts to create new Special Notice but does not have authorisation
    Given I am not logged in
    When I create a Special Notice
    Then the Special Notice is not created
