Feature:
  As an unregistered user in the process of registering an account
  I want to be able to confirm my account details after completing the registration steps
  So that I can use the MOT system

  @VM-11722
  Scenario Outline: A user registers the account successfully
    Given I am an unregistered user
    And For the "details" step I input:
      | firstName   | middleName   | lastName   | emailAddress   | confirmEmailAddress   |
      | <firstName> | <middleName> | <lastName> | <emailAddress> | <confirmEmailAddress> |
    And For the "address" step I input:
      | address1   | address2   | address3   | townOrCity | postcode |
      | <address1> | <address2> | <address3> | town       | PO57 0DE |
    And I supply valid answers to the security questions
    And I provide a valid password
    When I confirm my details
    Then an account is created
    Then I will be able to login

  Examples:
    | firstName | middleName | lastName         | emailAddress        | confirmEmailAddress | address1             | address2    | address3      |
    | John      | James      | Doe              | fake@email.com      | fake@email.com      | 123 address one      |             | address three |
    | Mary-Anne | Jane       | Smith            | fake123@email.com   | fake123@email.com   | address one          | address two | address three |
    | Jäné      | John       | Doe              | fake-mail@email.com | fake-mail@email.com | address one          | address two | address three |
    | Jane      | May        | Spencer-Campbell | fakemail@email.com  | fakemail@email.com  | address street       | address two | address three |
    | Jane      | May-Jane   | Smith            | fakemail@email.com  | fakemail@email.com  | 12(a) address street | address two | address three |
    | Jane      | May        | Smith            | fakemail@email.com  | fakemail@email.com  | 12 address street    | address two |               |
    | Jane      | May        | Smith            | fakemail@email.com  | fakemail@email.com  | 12 address street    |             |               |

  @VM-11722 @negative
  Scenario Outline: A user attempts to register an account but supplies invalid or insufficient details
    Given I am an unregistered user
    And For the "details" step I input:
      | firstName   | middleName   | lastName   | emailAddress   | confirmEmailAddress   |
      | <firstName> | <middleName> | <lastName> | <emailAddress> | <confirmEmailAddress> |
    And For the "address" step I input:
      | address1   | address2   | address3   | townOrCity | postcode |
      | <address1> | <address2> | <address3> | town       | PO57 0DE |
    And I supply valid answers to the security questions
    And I provide a valid password
    When I try to confirm my details
    Then an account is not created

  Examples:
    | firstName | middleName | lastName | emailAddress       | confirmEmailAddress | address1          | address2    | address3      |
    |           |            | Smith    | fake@email.com     | fake@email.com      | address street    | address two | address three |
    | John      |            |          | fake@email.com     | fake@email.com      | address           | addresstwo  | address3      |
    |           |            |          | fakemail@email.com | fakemail@email.com  | 12 address street |             |               |
    | Jane      |            | Smith    | fakemail@email.com | incorrect@email.com | 12 address street |             |               |
    | Jane      |            | Smith    | fakemail@email.com |                     | 12 address street |             |               |
    | Jane      |            | Smith    | fakemailemail.com  | fakemailemail.com   | 12 address street |             |               |
    | Jane      |            | Smith    | fakemail@email.com | fakemail@email.com  |                   |             |               |