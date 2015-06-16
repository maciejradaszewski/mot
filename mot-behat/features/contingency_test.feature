Feature: Contingency Test
  In order to record tests that were performed when the system was off-line
  As a Tester
  I wish to enter Contingency Tests results on the system (VM-4506)

  Scenario: Tester starts a Contingency MOT Test
    Given I am logged in as a Tester
    And I called the helpdesk to ask for a daily contingency code
    When I start a Contingency MOT test
    Then I should receive the MOT test number
    And the MOT Test Number should be 12 digits long

  Scenario Outline: Tester submits Contingency test details
    Given I am logged in as a Tester
    When I create a new <testType> contingency test with reason <reason>
    Then I should receive an emergency log id

  Examples:
    | testType | reason |
    | normal   | CP     |
    | normal   | PI     |
    | normal   | OT     |
    | retest   | CP     |
    | retest   | PI     |
    | retest   | OT     |

  Scenario Outline: Unauthenticated Tester attempts to submit Contingency test details (normal & retest types)
    Given I am not logged in
    When I attempt to create a new <testType> contingency test
    Then I should receive an Unauthorised response

  Examples:
    | testType |
    | normal   |
    | retest   |

  Scenario Outline: Tester submits Contingency test details with invalid test type
    Given I am logged in as a Tester
    When I attempt to create a new <testType> contingency test
    Then I should receive a Bad Request response

  Examples:
    | testType          |
    | invalid test type |
    | $$$$$             |
    | 1                 |
    | -1                |
    |                   |

  Scenario Outline: Tester attempts to submit Contingency test details with invalid contingency code
    Given I am logged in as a Tester
    When I attempt to create a new <testType> contingency test with a <contingencyCode>
    Then I should receive a Bad Request response

  Examples:
    | testType | contingencyCode |
    | normal   | 1               |
    | normal   | A               |
    | normal   | $               |
    | normal   |                 |
    | retest   | 1               |
    | retest   | A               |
    | retest   | $               |
    | retest   |                 |

  Scenario Outline: Invalid users cannot submit Contingency test details for Retest test type
    Given I'm authenticated with my username and password <username> DEFAULT
    When I attempt to create a new <testType> contingency test with a DEFAULT
    Then I should receive a Forbidden response

  Examples:
    | username        | testType |
    | areaoffice1user | retest   |
    | ae              | retest   |
    | areaadmin       | retest   |
    | schememgt       | retest   |
    | aedm            | retest   |
    | aed1            | retest   |
    | site-manager    | retest   |
    | inactivetester  | retest   |
    | ft-enf-tester   | retest   |
    | financeuser     | retest   |
    | csco            | retest   |
    | areaoffice1user | normal   |
    | ae              | normal   |
    | areaadmin       | normal   |
    | schememgt       | normal   |
    | aedm            | normal   |
    | aed1            | normal   |
    | site-manager    | normal   |
    | inactivetester  | normal   |
    | ft-enf-tester   | normal   |
    | financeuser     | normal   |
    | csco            | normal   |

  Scenario: Tester Completes a Contingency - PASSED MOT Test
    Given I am logged in as a Tester
    And I start a Contingency MOT test
    And the Tester adds an Odometer Reading of 658 mi
    And the Tester adds a Class 3-7 Decelerometer Brake Test
    When the Tester Passes the Mot Test
    Then the MOT Test Status is "PASSED"
    And the Contingency Test is Logged

  Scenario: Tester Completes a Contingency - FAILED MOT Test
    Given I am logged in as a Tester
    And I start a Contingency MOT test
    And the Tester adds an Odometer Reading of 120000 mi
    And the Tester adds a Class 3-7 Decelerometer Brake Test
    And the Tester adds a Reason for Rejection
    When the Tester Fails the Mot Test
    Then the MOT Test Status is "FAILED"
    And the Contingency Test is Logged

  Scenario: Tester Completes a Contingency - ABORTED MOT Test
    Given I am logged in as a Tester
    And I start a Contingency MOT test
    When the Tester Aborts the Mot Test
    Then the MOT Test Status is "ABORTED"
    And the Contingency Test is Logged