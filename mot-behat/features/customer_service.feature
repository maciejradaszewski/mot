Feature: Customer Service
  In order to Administer the MOT Application
  As a Customer Service Centre Operator
  I want a Administer Users records

  Scenario Outline: Valid search for a User returns result
    Given I am logged in as a Customer Service Operator
    When I Search for a Customer Service Operator with following data:
      | userName   | firstName   | lastName   | postCode   | dateOfBirth   |
      | <userName> | <firstName> | <lastName> | <postCode> | <dateOfBirth> |
    Then the Searched User data will be returned
  Examples:
    | userName | firstName | lastName       | postCode | dateOfBirth |
    | tester2  | Bob       | Arctor Tester2 | L1 1PQ   | 1981-04-24  |
    |          | Bob       | Arctor Tester2 | L1 1PQ   | 1981-04-24  |
    | tester2  |           | Arctor Tester2 | L1 1PQ   | 1981-04-24  |
    | tester2  | Bob       |                | L1 1PQ   | 1981-04-24  |
    | tester2  | Bob       | Arctor Tester2 |          | 1981-04-24  |
    | tester2  | Bob       | Arctor Tester2 | L1 1PQ   |             |
    |          |           | Arctor Tester2 | L1 1PQ   |             |

  Scenario Outline: Invalid search for a User returns no results
    Given I am logged in as a Customer Service Operator
    When I Search for a Customer Service Operator with following data:
      | userName   | firstName   | lastName   | postCode   | dateOfBirth   |
      | <userName> | <firstName> | <lastName> | <postCode> | <dateOfBirth> |
    Then the Searched User data will NOT be returned
  Examples:
    | userName | firstName | lastName       | postCode | dateOfBirth |
    | tester3  | Bob       | Arctor Tester2 | L1 1PQ   | 1981-04-24  |
    | tester2  | Bob1      | Arctor Tester2 | L1 1PQ   | 1981-04-24  |
    | tester2  | Bob       | Arctor Tester3 | L1 1PQ   | 1981-04-24  |
    | tester2  | Bob       | Arctor Tester2 | L2 1PQ   | 1981-04-24  |
    | tester2  | Bob       | Arctor Tester2 | L1 1PQ   | 1982-04-24  |

  Scenario: Valid User Details Searched Results
    Given I am logged in as a Customer Service Operator
    When I Search for a Valid User
    Then the Users data will be returned

  Scenario: Invalid User Details Searched Results
    Given I am logged in as a Customer Service Operator
    When I Search for a Invalid User
    Then the Users data will not be returned

  Scenario: Authorised Examiner information search
    Given I am logged in as a Customer Service Operator
    When I search for an existing Authorised Examiner
    Then I will see the Authorised Examiner's details

  Scenario: MOT test information search
    Given I am logged in as a Customer Service Operator
    Given a logged in Tester, starts an MOT Test
    When I search for an MOT test
    Then the MOT test data is returned

  Scenario: Non-existent Authorised Examiner information search
    Given I am logged in as a Customer Service Operator
    When I search for an Invalid Authorised Examiner
    Then I am informed that Authorised Examiner does not exist

  Scenario: Non-existent MOT test information search
    Given I am logged in as a Customer Service Operator
    When I search for an Invalid MOT test
    Then the MOT test is not found