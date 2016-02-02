Feature: Person
  As a Tester
  I want to get information Person Information
  So that I can get information related to myself and others

  Scenario: Classes for Person
    Given I am logged in as a Tester
    When I get Information about my MOT Classes
    Then I will see my Available Classes

  Scenario: Dashboard for Person
    Given I am logged in as a Tester
    When I get Information about my Dashboard
    Then I will see my Dashboard Information

  Scenario: Check Tester has MOT in Progress
    Given I am logged in as a Tester
    When I have an MOT Test In Progress
    And I get Information about my Dashboard
    Then my Dashboard will return the MotTestNumber

  Scenario: Check Tester details in Profile
    Given I am logged in as a Tester
    When I get my Profile details
    Then I will see my username in my Profile
    And I will see my user id in my Profile

  Scenario: Successfully update Area Office user email address
    Given I am logged in as an Area Office User
    When I update my email address on my profile
    Then I will see my updated email address

  Scenario: Successfully update Tester user email address
    Given I am logged in as a Tester
    When I update my email address on my profile
    Then I will see my updated email address

  Scenario: Tester cannot update their profile with mismatching email addresses
    Given I am logged in as a Tester
    When I update my profile with a mismatching email address
    Then my email address will not be updated
    And I should receive an email mismatch message in the response

  Scenario: Area Office User cannot update their profile with mismatching email addresses
    Given I am logged in as an Area Office User
    When I update my profile with a mismatching email address
    Then my email address will not be updated
    And I should receive an email mismatch message in the response

  Scenario Outline: Area Office user attempts to change email that violates validation
    Given I am logged in as an Area Office User
    When I update my email address to <email>
    Then my email address will not be updated

    Examples:
      | email |
      | .     |
      | .com  |
      | com   |
      | @     |

  Scenario Outline: Email validation is enforced on User Profile for Tester
    Given I am logged in as a Tester
    When I update my email address to <email>
    Then my email address will not be updated

    Examples:
      | email |
      | .     |
      | .com  |
      | com   |
      | @     |

  @wip
  Scenario: AE record contains Data Disclosure
    Given I am logged in as an Area Office User
    When I search for an Authorised Examiner
    Then the Authorised Examiner record contains Data Disclosure data

  Scenario: A Person can view their test logs
    Given I am logged in as a Tester
    And I have created 2 mot tests
    When I review my test logs
    Then 2 test logs should show today in summary section
    And My test logs should return 2 detailed records

  @driving-licence
  Scenario: An Area Office User can add a licence to a tester's profile
    Given I am logged in as an Area Office User
    And I have selected a user who needs to have a licence added to their profile
    When I add a licence 'smith711215jb9az' to the user's profile
    Then their licence should match 'SMITH711215JB9AZ'

  @driving-licence
  Scenario: A Scheme manager can add a licence to a tester's profile
    Given I am logged in as a Scheme Manager
    And I have selected a user who needs to have a licence added to their profile
    When I add a licence '11223344' with the region 'NI' to the user's profile
    Then their licence should match '11223344'

  @driving-licence
  Scenario: An Area Office User can update a licence on a tester's profile
    Given I am logged in as an Area Office User
    And I have selected a user who needs to have their licence edited
    When I update the licence to 'SMITH711215JB9AZ'
    Then their licence should match 'SMITH711215JB9AZ'

  @driving-licence
  Scenario: A Scheme user can update a tester's licence and region
    Given I am logged in as an Scheme User
    And I have selected a user who needs to have their licence edited
    When I update the licence to '11223344' and the region 'NI'
    Then their licence should match '11223344'

  @driving-licence
  Scenario: An Area Office User cannot add an invalid licence to a tester's profile
    Given I am logged in as an Area Office User
    And I have selected a user who needs to have a licence added to their profile
    When I add a licence 'IAMINVALID' to the user's profile
    Then the user should not have a licence associated with their account

  @driving-licence
  Scenario: An Area Office User cannot add an invalid licence and region to a tester's profile
    Given I am logged in as an Area Office User
    And I have selected a user who needs to have a licence added to their profile
    When I add a licence 'IAMINVALID' to the user's profile
    Then the user should not have a licence associated with their account

  @driving-licence
  Scenario: An Area Office User cannot update a tester's licence to invalid data
    Given I am logged in as an Area Office User
    And I have selected a user who needs to have their licence edited
    When I update the licence to 'IAMINVALID'
    Then their licence should not match 'IAMINVALID'

  @driving-licence
  Scenario: An Area Office User can delete a licence on a tester's profile
    Given I am logged in as an Area Office User
    And I have selected a user who needs to have their licence deleted
    When I delete the user's licence
    Then the user should not have a licence associated with their account

  Scenario: A user with permission to change names can update a different person's name
    Given I am logged in as a Scheme Manager
    When I change a person's name to Joe Bloggs Smith
    Then The person's name should be updated

  Scenario: A user with permission to change names cannot update their own name
    Given I am logged in as a Area Office 1
    When I change my own name to Joe Bloggs Smith
    Then I am forbidden

  Scenario: A user without permission to change names should be forbidden
    Given I am logged in as a Tester
    When I change a person's name to Joe Bloggs Smith
    Then I am forbidden

  Scenario Outline: Name validation is enforced
    Given I am logged in as an Scheme User
    When I change a person's name to <firstName> <middleName> <lastName>
    Then The person's name should not be updated

    Examples:
      | firstName                                      | middleName                                     | lastName                                        |
      |                                                |                                                |                                                 |
      | Joe                                            |                                                |                                                 |
      |                                                |                                                |  Bloggs                                         |
      | Thisnameislongerthan45characterssoitisinvalidl |                                                |                                                 |
      | Joe                                            | Thisnameislongerthan45characterssoitisinvalidl |  Bloggs                                         |
      | Joe                                            |                                                |  Thisnameislongerthan45characterssoitisinvalidl |
