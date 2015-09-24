Feature: Permissions
  As the Roles Based Access Control process
  I want to implement user permissions and roles
  So that I can control how users interact with the application

  Scenario Outline: Vehicle Examiner is not authorised to perform an MOT Test for any Class
    Given I am logged in as a Vehicle Examiner
    When I attempt to start an Mot Test for a class <class> vehicle
    Then I should receive a Forbidden response
  Examples:
    | class |
    | 1     |
    | 2     |
    | 3     |
    | 4     |
    | 5     |
    | 7     |

  @story @VM-4497
  Scenario Outline: Users with no permission to test cannot start an MOT test
    Given I am logged in as a Tester
    And I Create a new Vehicle Technical Record with Class of <class>
    And I'm authenticated with my username and password <username> <password>
    When I attempt to start an Mot Test with a Class <class> Vehicle
    Then an MOT test number should not be allocated
  Examples:
    | username          | password | class |
    | cron-job          | DEFAULT  | 3     |
    | novtstester       | DEFAULT  | 3     |
    | areaadmin         | DEFAULT  | 4     |
    | schememgt         | DEFAULT  | 5     |
    | csco              | DEFAULT  | 7     |
    | aed1              | DEFAULT  | 3     |
    | aedm              | DEFAULT  | 4     |
    | siteManagerAtVts1 | DEFAULT  | 7     |
    | schemeuser        | DEFAULT  | 3     |
    | do                | DEFAULT  | 4     |
    | ft-enf-tester     | DEFAULT  | 4     |

  Scenario Outline: Validate Tester permissions
    Given I am logged in as a Tester
    When I get my Profile details
    Then my profile will contain the role "<roles>"

  Examples:
    | roles         |
    | TESTER        |
    | TESTER-ACTIVE |
    | USER          |

  @story @VM-9865
  Scenario Outline: Vehicle Examiner can view the summary of a MOT test that is completed
    Given there is a "<status>" "<test>" MOT test
    When I log in as a Vehicle Examiner
    Then I can view the "<test>" MOT summary

  Examples:
    | test | status |
    | demo | passed |
    | demo | failed |
    | normal | passed |
    | normal | failed |
