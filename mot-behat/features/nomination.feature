Feature: Nomination
  As a valid user
  I want to nominate user to site or organisation role
  So that user can have new role

  Scenario Outline: A manager nominates user to site role
    Given I am logged in as <user_manager> to new site
    And I nominate user to <role_code> role
    When a user accepts nomination to "<role_name>" site role
    Then a user has new site role "<role_code>"
    And a site event is generated for the site of "Role Association Change"
  Examples:
    | user_manager          | role_code         | role_name    |
    | an Area Office User   | TESTER       | Tester       |
    | an Area Office User   | SITE-MANAGER | Site manager |
    | an Area Office User   | SITE-ADMIN   | Site admin   |
    | an Area Office User 2 | TESTER       | Tester       |
    | an Area Office User 2 | SITE-MANAGER | Site manager |
    | an Area Office User 2 | SITE-ADMIN   | Site admin   |
    | a Site Manager        | SITE-ADMIN   | Site admin   |
    | a Site Manager        | TESTER       | Tester       |

  Scenario Outline: A Manager nominates user to organisation role
    Given I am logged in as <user_manager> to new organisation
    And I nominate user to <role> role
    When a user accepts nomination to "<role>" organisation role
    Then a user has new organisation role "<role_short_name>"
    Then an organisation event is generated for the organisation of "Role Association Change"
  Examples:
    | user_manager | role                         | role_short_name |
    | an Aedm      | Authorised Examiner Delegate | AED             |

  Scenario Outline: A DVSA user nominates a new user to organisation role
    Given I am logged in as <user_manager>
    And I nominate user to <role> role to new organisation
    When a user accepts nomination to "<role>" organisation role
    Then a user has new organisation role "<role_short_name>"
    Then an organisation event is generated for the organisation of "Role Association Change"
  Examples:
    | user_manager          | role                                   | role_short_name |
    | an Area Office User   | Authorised Examiner Delegate           | AED             |
    | an Area Office User 2 | Authorised Examiner Delegate           | AED             |

  Scenario Outline: A DVSA user nominates a new user as AEDM to organisation role
    Given I am logged in as <user_manager>
    And I nominate user to <role> role to new organisation
    Then a user has new organisation role "<role_short_name>"
    Then an organisation event is generated for the organisation of "Role Association Change"
  Examples:
    | user_manager          | role                                   | role_short_name |
    | an Area Office User   | Authorised Examiner Designated Manager | AEDM            |
    | an Area Office User 2 | Authorised Examiner Designated Manager | AEDM            |

  Scenario Outline: A DVSA user denominates user to organisation role
    Given I am logged in as <user_manager>
    And I nominate user to <role> role to new organisation
    When a user accepts nomination to "<role>" organisation role
    Then a user has new organisation role "<role_short_name>"
    Then an organisation event is generated for the organisation of "Role Association Change"
    And I denominate user to <role> role to new organisation
    Then a user does not have organisation role "<role_short_name>"
  Examples:
    | user_manager          | role                                   | role_short_name |
    | an Area Office User   | Authorised Examiner Delegate           | AED             |
    | an Area Office User 2 | Authorised Examiner Delegate           | AED             |
    | an Area Office User   | Authorised Examiner Designated Manager | AEDM            |
    | an Area Office User 2 | Authorised Examiner Designated Manager | AEDM            |