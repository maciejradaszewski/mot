Feature: Nomination
  As a valid user
  I want to nominate user to site or organisation role
  So that user can have new role

  Scenario Outline: A manager nominates user to site role
    Given I am logged in as <user_manager> to new site
    And I nominate user to <role> role
    When a user accepts nomination to "<role>" site role
    Then a user has new site role "<role>"
    And a site event is generated for the site of "Role Association Change"
    Examples:
    |user_manager       |role        |
    |an Area Office User|TESTER      |
    |an Area Office User|SITE-MANAGER|
    |an Area Office User|SITE-ADMIN  |
    |a Site Manager     |SITE-ADMIN  |
    |a Site Manager     |TESTER      |

  Scenario Outline: A manager nominates user to organisation role
    Given I am logged in as <user_manager> to new organisation
    And I nominate user to <role> role
    When a user accepts nomination to "<role>" organisation role
    Then a user has new organisation role "<role_short_name>"
    Then an organisation event is generated for the organisation of "Role Association Change"
  Examples:
    |user_manager       |role                        |role_short_name|
    |an Aedm            |Authorised examiner delegate|AED            |
