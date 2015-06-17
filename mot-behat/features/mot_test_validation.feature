Feature: MOT Test Validation
  As a System User
  I want to deviate the MOT Test business process
  So that I can confirm Tests cannot be invalidated

  Scenario Outline: Validate a "Passed" MOT test cannot be "Failed" for Classes 3-7
    Given I am logged in as a Tester
    And I start an Mot Test with a Class <class> Vehicle
    And the Tester adds an Odometer Reading of 1000 mi
    And the Tester adds a Class 3-7 Decelerometer Brake Test
    When the Tester Fails the Mot Test
    Then the Test will not be Failed as there are no Failures
  Examples:
    | class |
    | 3     |
    | 4     |
    | 5     |
    | 7     |

  Scenario Outline: Validate a "Passed" MOT test cannot be "Failed" for Classes 1-2
    Given I am logged in as a Tester
    And I start an Mot Test with a Class <class> Vehicle
    And the Tester adds an Odometer Reading of 1000 mi
    And the Tester adds a Class 1-2 Decelerometer Brake Test
    When the Tester Fails the Mot Test
    Then the Test will not be Failed as there are no Failures
  Examples:
    | class |
    | 1     |
    | 2     |

  Scenario Outline: Validate a "Failed" MOT test cannot be "Passed" for Classes 3-7
    Given I am logged in as a Tester
    And I start an Mot Test with a Class <class> Vehicle
    And the Tester adds an Odometer Reading of 1000 mi
    And the Tester adds a Class 3-7 Decelerometer Brake Test
    And the Tester adds a Reason for Rejection
    When the Tester Passes the Mot Test
    Then the Test will not be Passed as there are Failures
  Examples:
    | class |
    | 3     |
    | 4     |
    | 5     |
    | 7     |

  @defect @vm-4877 @disabled
  Scenario: Validate an already "Passed" Mot test cannot be "Aborted"
    Given I am logged in as a Tester
    And I start an Mot Test with a Class 4 Vehicle
    And the Tester adds an Odometer Reading of 1000 mi
    And the Tester adds a Class 3-7 Decelerometer Brake Test
    And the Tester Passes the Mot Test
    When the Tester Aborts the Mot Test
    Then the Test will not be Aborted as the Test is Complete

  @defect @vm-4877 @disabled
  Scenario: Validate an already "Failed" Mot test cannot be "Aborted"
    Given I am logged in as a Tester
    And I start an Mot Test with a Class 4 Vehicle
    And the Tester adds an Odometer Reading of 1000 mi
    And the Tester adds a Class 3-7 Decelerometer Brake Test
    And the Tester adds a Reason for Rejection
    And the Tester Fails the Mot Test
    When the Tester Aborts the Mot Test
    Then the Test will not be Aborted as the Test is Complete

  Scenario: Validate a Test without Odometer cannot be completed
    Given I am logged in as a Tester
    And I start an Mot Test with a Class 4 Vehicle
    And the Tester adds a Class 3-7 Decelerometer Brake Test
    When the Tester Passes the Mot Test
    Then the Test will not Complete as it's In Progress

  Scenario: Validate a Test without Brake Test cannot be completed
    Given I am logged in as a Tester
    And I start an Mot Test with a Class 4 Vehicle
    And the Tester adds an Odometer Reading of 1000 mi
    When the Tester Passes the Mot Test
    Then the Test will not Complete as it's In Progress

  Scenario Outline: Complete MOT Test with invalid Odometer reading
    Given a logged in Tester, starts an MOT Test
    And the Tester attempts to add an Odometer Reading of <distance>
    Then the odometer reading is rejected
  Examples:
    | distance |
    | 499 mk   |
    | -1 mi    |
    | -1 km    |

  Scenario: Vehicle Examiner aborts an In Progress MOT Test
    Given a logged in Tester, starts an MOT Test
    And I am logged in as a Vehicle Examiner
    When a logged in Vehicle Examiner aborts the test
    Then the MOT Test Status is "ABORTED_VE"

  Scenario Outline: Tester with zero slot balance cannot perform an MOT Test
    Given I am logged in as a Tester
    And I start an Mot Test with a Class <class> Vehicle
    And I'm authenticated with my username and password noslotstester default
    When I attempt to start an Mot Test with a Class <class> Vehicle
    Then an MOT test number should not be allocated
  Examples:
    | class |
    | 1     |
    | 2     |
    | 3     |
    | 4     |
    | 5     |
    | 7     |