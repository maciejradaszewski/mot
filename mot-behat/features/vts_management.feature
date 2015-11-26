Feature: Vehicle Testing Station management
  In order to manage vehicle testing organisations on the system
  As a DVSA Administrator
  I want to edit Site details

  @VM-10407
  Scenario: Update test lanes for One person test lane and Two people test lane successfully
    Given I am logged in as an Area Office User
    And a "Popular Garage" vehicle testing site exists
    When I configure its test lines to:
      | number of one person test lanes | number of two person test lanes |
      | 2                               | 3                               |
    Then site details should be updated

  Scenario: Update test lanes for One person test lane and Two people test lane unsuccessfully
    Given I am logged in as an Area Office User
    And a "Popular Garage" vehicle testing site exists
    When I configure its test lines to:
      | number of one person test lanes | number of two person test lanes |
      | 0                               | 0                               |
    Then My changes should not be updated

  Scenario: Update without selecting number of test lanes
    Given I am logged in as an Area Office User
    And a "Popular Garage" vehicle testing site exists
    When I configure its test lines to:
      | number of one person test lanes | number of two person test lanes |
      | please select                   | please select                   |
    Then My changes should not be updated

  Scenario: Customer Service Operator update test lanes without permission
    Given I am logged in as a Customer Service Operator
    And a "Popular Garage" vehicle testing site exists
    When I configure its test lines to:
      | number of one person test lanes | number of two person test lanes |
      | 2                               | 2                               |
    Then Site details should not be updated

  Scenario Outline: Change site status
    Given I am logged in as an Area Office User
    And a "Popular Garage" vehicle testing site exists
    When I change the site status to <site_status_attribute>
    Then my status should be updated
    Examples:
      | site_status_attribute |
      | Applied               |
      | Approved              |
      | Lapsed                |
      | Rejected              |
      | Retracted             |
      | Extinct               |

  @BL-102
  Scenario: Permitted user can remove all test classes from a site
    Given I am logged in as an Area Office User
    And a "Popular Garage" vehicle testing site exists
    When I remove all test classes
    Then site testing classes shoud be removed

  @BL-102
  Scenario: Cannot start MOT test if site has no testing classes
    Given I am logged in as an Area Office User to new site
    And I remove all test classes
    And I log in as a tester assigned to newly created site with no test classes
    When I try to start MOT test
    Then I am not permitted to do this