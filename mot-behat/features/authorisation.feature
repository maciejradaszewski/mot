@functional
Feature: Authorisation of requests

  Scenario: Unable to POST to vehicle-testing-station/:id when not logged in
    Given I am not logged in
    When I make a "POST" request to "vehicle-testing-station/1"
    Then I should receive an Unauthorised response
    And I should not see any data in the response body
    And I should see "Unauthorised" in the error message

  Scenario: Unable to GET site/:id/person/:id/role when not logged in
    Given I am not logged in
    When I make a "GET" request to "site/1/person/1/role"
    Then I should receive an Unauthorised response
    And I should not see any data in the response body
    And I should see "Unauthorised" in the error message

  Scenario: Unable to GET mot-test-search when not logged in
    Given I am not logged in
    When I make a "GET" request to "mot-test-search"
    Then I should receive an Unauthorised response
    And I should not see any data in the response body
    And I should see "Unauthorised" in the error message

  Scenario Outline: Unable to GET /mot-test-search?:searchParam=:value&format=:format when not logged in
    Given I am not logged in
    When I make a "GET" request to "<endpoint>"
    Then I should receive an Unauthorised response
    And I should not see any data in the response body
    And I should see "Unauthorised" in the error message
  Examples:
    | endpoint                                            |
    | mot-test-search?siteNumber=V1234&format=DATA_TABLES |
    | mot-test-search?testerId=1&format=DATA_TABLES       |