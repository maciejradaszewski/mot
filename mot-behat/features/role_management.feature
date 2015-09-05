Feature: Role Management
  As a valid user
  I want to be able to assign roles to other users
  So that I can delegate tasks to other users

  @VM-10619 @VM-10737 @VM-10722 @VM-10723 @VM-11697
  Scenario Outline: A scheme manager adds a role to another user
    When I am logged in as a <manager>
    And I add the role of "<role_name>" to another user
    Then the user's RBAC will have the role "<role_name>"
    And a status change event is generated for the user of "Role Association Change"
    And an event description contains my name
    And an event description contains phrase "<role_full_name>"
    And the user will receive a "DVSA Assign Role" notification
    And a notification subject contains phrase "<role_full_name>"
  Examples:
    | manager                  | role_name                         | role_full_name             |
    | Scheme Manager           | DVSA-SCHEME-MANAGEMENT            | Scheme manager             |
    | Scheme Manager           | DVSA-SCHEME-USER                  | Scheme user                |
    | Scheme Manager           | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Customer service operative |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | Customer service manager   |
    | Scheme Manager           | DVSA-AREA-OFFICE-1                | Area office 1              |
    | Scheme Manager           | DVSA-AREA-OFFICE-2                | Area office 2              |
    | Scheme Manager           | VEHICLE-EXAMINER                  | Vehicle examiner           |
    | Scheme Manager           | FINANCE                           | Finance                    |
    | Scheme Manager           | DVLA-OPERATIVE                    | DVLA operative             |
    | Customer Service Manager | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Customer service operative |
    | DVLA Manager             | DVLA-OPERATIVE                    | DVLA operative             |

  @VM-10619 @VM-10737 @VM-10722 @VM-10723 @VM-11697
  Scenario Outline: A permitted user can add an internal role to a user that does not have a trade role
    Given I am logged in as a <permitted user>
    And The user "<non trade user>" exists
    When I add the role of "<role>" to the user
    Then the user's RBAC will have the role "<role>"
  Examples:
    | permitted user           | role                              | non trade user             |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | Scheme User                |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | Finance User               |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | Vehicle Examiner           |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | DVLA Operative             |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | Area Office User           |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | Area Office User 2         |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | DVLA Manager               |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | Customer Service Operative |
    | Scheme Manager           | FINANCE                           | Scheme User                |
    | Scheme Manager           | FINANCE                           | Customer Service Operative |
    | Scheme Manager           | FINANCE                           | Area Office User           |
    | Scheme Manager           | DVLA-OPERATIVE                    | Area Office User 2         |
    | Scheme Manager           | FINANCE                           | DVLA Manager               |
    | Scheme Manager           | FINANCE                           | DVLA Operative             |
    | Scheme Manager           | FINANCE                           | Customer Service Manager   |
    | Scheme Manager           | FINANCE                           | Vehicle Examiner           |
    | Scheme Manager           | DVLA-OPERATIVE                    | DVLA Manager               |
    | Scheme Manager           | DVLA-OPERATIVE                    | Scheme User                |
    | Scheme Manager           | DVLA-OPERATIVE                    | Customer Service Operative |
    | Scheme Manager           | DVLA-OPERATIVE                    | Area Office User           |
    | Scheme Manager           | DVLA-OPERATIVE                    | Area Office User 2         |
    | Scheme Manager           | DVLA-OPERATIVE                    | DVLA Manager               |
    | Scheme Manager           | DVLA-OPERATIVE                    | Customer Service Manager   |
    | Scheme Manager           | DVLA-OPERATIVE                    | Finance User               |
    | Scheme Manager           | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Finance User               |
    | Scheme Manager           | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Scheme User                |
    | Scheme Manager           | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Customer Service Manager   |
    | Scheme Manager           | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Area Office User           |
    | Scheme Manager           | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Area Office User 2         |
    | Scheme Manager           | CUSTOMER-SERVICE-CENTRE-OPERATIVE | DVLA Manager               |
    | Scheme Manager           | CUSTOMER-SERVICE-CENTRE-OPERATIVE | DVLA Operative             |
    | Scheme Manager           | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Vehicle Examiner           |
    | Scheme Manager           | DVSA-SCHEME-USER                  | Finance User               |
    | Scheme Manager           | DVSA-SCHEME-USER                  | Customer Service Manager   |
    | Scheme Manager           | DVSA-SCHEME-USER                  | DVLA Operative             |
    | Scheme Manager           | DVSA-SCHEME-USER                  | DVLA Manager               |
    | Scheme Manager           | DVSA-SCHEME-USER                  | Vehicle Examiner           |
    | Scheme Manager           | DVSA-SCHEME-USER                  | Area Office User           |
    | Scheme Manager           | DVSA-SCHEME-USER                  | Area Office User 2         |
    | Scheme Manager           | DVSA-SCHEME-USER                  | Customer Service Operative |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | Finance User               |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | DVLA Operative             |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | DVLA Manager               |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | Customer Service Operative |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | Area Office User           |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | Area Office User 2         |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | Vehicle Examiner           |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          | Scheme User                |
    | Scheme Manager           | DVSA-AREA-OFFICE-1                | Scheme User                |
    | Scheme Manager           | DVSA-AREA-OFFICE-1                | Vehicle Examiner           |
    | Scheme Manager           | DVSA-AREA-OFFICE-1                | Customer Service Operative |
    | Scheme Manager           | DVSA-AREA-OFFICE-1                | DVLA Manager               |
    | Scheme Manager           | DVSA-AREA-OFFICE-1                | DVLA Operative             |
    | Scheme Manager           | DVSA-AREA-OFFICE-1                | Finance User               |
    | Scheme Manager           | VEHICLE-EXAMINER                  | Finance User               |
    | Scheme Manager           | VEHICLE-EXAMINER                  | DVLA Operative             |
    | Scheme Manager           | VEHICLE-EXAMINER                  | DVLA Manager               |
    | Scheme Manager           | VEHICLE-EXAMINER                  | Customer Service Operative |
    | Scheme Manager           | VEHICLE-EXAMINER                  | Scheme User                |
    | Scheme Manager           | VEHICLE-EXAMINER                  | Area Office User           |
    | Scheme Manager           | VEHICLE-EXAMINER                  | Area Office User 2         |
    | Scheme Manager           | VEHICLE-EXAMINER                  | Customer Service Manager   |
    | Scheme User              | FINANCE                           | Customer Service Manager   |
    | Scheme User              | FINANCE                           | Area Office User           |
    | Scheme User              | FINANCE                           | Area Office User 2         |
    | Scheme User              | FINANCE                           | DVLA Manager               |
    | Scheme User              | FINANCE                           | Customer Service Operative |
    | Scheme User              | FINANCE                           | Vehicle Examiner           |
    | Customer Service Manager | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Vehicle Examiner           |
    | Customer Service Manager | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Finance User               |
    | Customer Service Manager | CUSTOMER-SERVICE-CENTRE-OPERATIVE | DVLA Manager               |
    | Customer Service Manager | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Customer Service Manager   |
    | Customer Service Manager | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Area Office User           |
    | Customer Service Manager | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Area Office User 2         |
    | Customer Service Manager | CUSTOMER-SERVICE-CENTRE-OPERATIVE | DVLA Operative             |
    | DVLA Manager             | DVLA-OPERATIVE                    | Area Office User           |
    | DVLA Manager             | DVLA-OPERATIVE                    | Area Office User 2         |
    | DVLA Manager             | DVLA-OPERATIVE                    | Customer Service Manager   |
    | DVLA Manager             | DVLA-OPERATIVE                    | Vehicle Examiner           |
    | DVLA Manager             | DVLA-OPERATIVE                    | Customer Service Operative |
    | DVLA Manager             | DVLA-OPERATIVE                    | Finance User               |
    | DVLA Manager             | DVLA-OPERATIVE                    | DVLA Manager               |
    | Area Office User         | VEHICLE-EXAMINER                  | DVLA Manager               |
    | Area Office User         | VEHICLE-EXAMINER                  | Finance User               |
    | Area Office User         | VEHICLE-EXAMINER                  | Customer Service Operative |
    | Area Office User         | VEHICLE-EXAMINER                  | Scheme User                |
    | Area Office User         | VEHICLE-EXAMINER                  | DVLA Operative             |
    | Area Office User         | VEHICLE-EXAMINER                  | Customer Service Manager   |
    | Area Office User         | DVSA-AREA-OFFICE-2                | DVLA Manager               |
    | Area Office User         | DVSA-AREA-OFFICE-2                | Finance User               |
    | Area Office User         | DVSA-AREA-OFFICE-2                | Customer Service Operative |
    | Area Office User         | DVSA-AREA-OFFICE-2                | Scheme User                |
    | Area Office User         | DVSA-AREA-OFFICE-2                | DVLA Operative             |
    | Area Office User         | DVSA-AREA-OFFICE-2                | Customer Service Manager   |
    | Scheme Manager           | FINANCE                           | Scheme User                |
    | Scheme Manager           | DVLA-OPERATIVE                    | DVLA Manager               |
    | Scheme Manager           | CUSTOMER-SERVICE-CENTRE-OPERATIVE | DVLA Manager               |
    | DVLA Manager             | DVLA-OPERATIVE                    | Scheme User                |

  # We have deliberately only tested a selection of the possibilities here.. not the entire grid matrix.
  # We were told not to slow down behat too much and that the main coverage is done through selenium.
  @negative @VM-10619 @VM-10737 @VM-10722 @VM-10723 @VM-11697
  Scenario Outline: An unpermitted user can not add an internal role to a user
    Given I am logged in as an <unpermitted user>
    And The user "<non trade user>" exists
    When I add the role of "<role>" to the user
    Then the user's RBAC will not have the role "<role>"
  Examples:
    | unpermitted user          | role                              | non trade user   |
    | Scheme User               | DVLA-OPERATIVE                    | DVLA Manager     |
    | Scheme User               | CUSTOMER-SERVICE-CENTRE-OPERATIVE | DVLA Manager     |
    | Customer Service Operator | DVLA-OPERATIVE                    | Scheme User      |
    | DVLA Manager              | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Vehicle Examiner |
    | Area Office User          | DVLA-OPERATIVE                    | Scheme User      |
    | Area Office User 2        | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Vehicle Examiner |
    | Vehicle Examiner          | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Area Office User |

  @VM-5041 @VM-11697
  Scenario Outline: A permitted user can remove a role from a user
    Given I am logged in as a <permitted user>
    And The user "<user>" exists
    And The user has the role "<role>"
    When I remove the role of "<role>" from the user
    Then the user's RBAC will not have the role "<role>"
    And a status change event is generated for the user of "Role Association Change"
    And an event description contains my name
    And an event description contains phrase "<role_full_name>"
    And the user will receive a "DVSA Remove Role" notification
    And a notification subject contains phrase "<role_full_name>"
  Examples:
    | permitted user | role                              | role_full_name             | user         |
    | Scheme Manager | FINANCE                           | Finance                    | DVLA Manager |
    | Scheme Manager | DVLA-OPERATIVE                    | DVLA operative             | DVLA Manager |
    | Scheme Manager | CUSTOMER-SERVICE-CENTRE-OPERATIVE | Customer service operative | DVLA Manager |
    | DVLA Manager   | DVLA-OPERATIVE                    | DVLA operative             | Scheme User  |

  # Self management
  @VM-11244 @VM-11697
  Scenario Outline: Check permitted DVSA users can not allocate a role to themselves
    Given I am logged in as a <permitted user>
    When I try to add the role of "<role>" to myself
    Then I should receive a Forbidden response
    And my RBAC will not contain the role "<role>"
  Examples:
    | permitted user           | role                              |
    | Scheme Manager           | DVSA-SCHEME-USER                  |
    | Scheme Manager           | FINANCE                           |
    | Scheme Manager           | CUSTOMER-SERVICE-MANAGER          |
    | Scheme Manager           | CUSTOMER-SERVICE-CENTRE-OPERATIVE |
    | Scheme Manager           | DVSA-AREA-OFFICE-1                |
    | Scheme Manager           | DVSA-AREA-OFFICE-2                |
    | Scheme Manager           | VEHICLE-EXAMINER                  |
    | Scheme User              | FINANCE                           |
    | Customer Service Manager | CUSTOMER-SERVICE-CENTRE-OPERATIVE |
    | DVLA Manager             | DVLA-OPERATIVE                    |
    | Area Office User         | DVSA-AREA-OFFICE-2                |
    | Area Office User         | VEHICLE-EXAMINER                  |

  @VM-11244 @VM-11697
  Scenario Outline: Check permitted DVSA users can not remove a role from themselves
    Given I am logged in as a <permitted user>
    And my RBAC has the role "<role>"
    When I try to remove the role of "<role>" from myself
    Then I should receive a Forbidden response
    And my RBAC will still have the role "<role>"
  Examples:
    | permitted user            | role                              |
    | Scheme Manager            | DVSA-SCHEME-MANAGEMENT            |
    | Scheme User               | DVSA-SCHEME-USER                  |
    | Customer Service Manager  | CUSTOMER-SERVICE-MANAGER          |
    | Customer Service Operator | CUSTOMER-SERVICE-CENTRE-OPERATIVE |
    | Area Office User          | DVSA-AREA-OFFICE-1                |
    | Finance User              | FINANCE                           |
