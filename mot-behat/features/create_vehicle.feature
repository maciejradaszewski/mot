Feature: Create new vehicle record
  In order to MOT test vehicles that do not exist in the system
  As a Tester
  I want a facility to create new vehicle records

  Scenario Outline: Create a new vehicle record for all Classes
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with Class of <class>
    Then the Vehicle Record is Created
    Examples:
      | class |
      | 1     |
      | 2     |
      | 3     |
      | 4     |
      | 5     |
      | 7     |

  Scenario Outline: Unauthorised user attempts to create new vehicle record
    Given I am not logged in
    When I Create a new Vehicle Technical Record with Class of <class>
    Then I should receive an Unauthorised response
    Examples:
      | class |
      | 1     |
      | 2     |
      | 3     |
      | 4     |
      | 5     |
      | 7     |

  Scenario Outline: Create Vehicle Technical Record with all fuel types
    Given I am logged in as a Tester
    When I create a Vehicle of Class <class> and Fuel Type <fuelType>
    Then the Vehicle Record is Created
    Examples:
      | class | fuelType |
      | 1     | PE       |
      | 1     | DI       |
      | 1     | EL       |
      | 1     | ST       |
      | 1     | CN       |
      | 1     | LN       |
      | 1     | LP       |
      | 1     | FC       |
      | 1     | OT       |
      | 2     | PE       |
      | 2     | DI       |
      | 2     | EL       |
      | 2     | ST       |
      | 2     | CN       |
      | 2     | LN       |
      | 2     | LP       |
      | 2     | FC       |
      | 2     | OT       |
      | 3     | PE       |
      | 3     | DI       |
      | 3     | EL       |
      | 3     | ST       |
      | 3     | CN       |
      | 3     | LN       |
      | 3     | LP       |
      | 3     | FC       |
      | 3     | OT       |
      | 4     | PE       |
      | 4     | DI       |
      | 4     | EL       |
      | 4     | ST       |
      | 4     | CN       |
      | 4     | LN       |
      | 4     | LP       |
      | 4     | FC       |
      | 4     | OT       |
      | 5     | PE       |
      | 5     | DI       |
      | 5     | EL       |
      | 5     | ST       |
      | 5     | CN       |
      | 5     | LN       |
      | 5     | LP       |
      | 5     | FC       |
      | 5     | OT       |
      | 7     | PE       |
      | 7     | DI       |
      | 7     | EL       |
      | 7     | ST       |
      | 7     | CN       |
      | 7     | LN       |
      | 7     | LP       |
      | 7     | FC       |
      | 7     | OT       |

  Scenario: Create a Class 1 vehicle record with various transmission types
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with the following data:
      | class | make  | model | fuelType | transmissionType | countryOfRegistration | cylinderCapacity | dateOfFirstUse |
      | 1     | Suzuk | Band  | PE       | 1                | 1                     | 1200             | 1990-01-31     |
      | 1     | Suzuk | Band  | PE       | 1                | 2                     | 1200             | 2014-01-31     |
      | 1     | Suzuk | Band  | PE       | 2                | 3                     | 1200             | 1990-01-31     |
      | 1     | Suzuk | Band  | PE       | 2                | 4                     | 1200             | 2014-01-31     |
    Then the Vehicle Records are Created

  Scenario: Create a Class 2 vehicle record with various transmission types
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with the following data:
      | class | make  | model | fuelType | transmissionType | countryOfRegistration | cylinderCapacity | dateOfFirstUse |
      | 2     | Suzuk | Haya  | PE       | 1                | 1                     | 1200             | 1990-01-31     |
      | 2     | Suzuk | Haya  | PE       | 1                | 2                     | 1200             | 2014-01-31     |
    Then the Vehicle Records are Created

  Scenario: Create a Class 3 vehicle record with various transmission types
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with the following data:
      | class | make  | model | fuelType | transmissionType | countryOfRegistration | cylinderCapacity | dateOfFirstUse |
      | 3     | Piagg | MP3   | PE       | 1                | 1                     | 1598             | 1990-01-31     |
      | 3     | Piagg | MP3   | PE       | 2                | 2                     | 1598             | 2014-01-31     |
    Then the Vehicle Records are Created

  Scenario: Create a Class 4 vehicle record with various transmission types
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with the following data:
      | class | make | model | fuelType | transmissionType | countryOfRegistration | cylinderCapacity | dateOfFirstUse |
      | 4     | BMW  | Mini  | PE       | 1                | 1                     | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 2                     | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 2                | 3                     | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 1                | 4                     | 1598             | 2014-01-31     |
    Then the Vehicle Records are Created

  Scenario: Create a Class 5 vehicle record with various transmission types
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with the following data:
      | class | make | model | fuelType | transmissionType | countryOfRegistration | cylinderCapacity | dateOfFirstUse |
      | 5     | Ford | Supe  | PE       | 1                | 1                     | 1598             | 1990-01-31     |
      | 5     | Ford | Supe  | DI       | 2                | 2                     | 1598             | 2014-01-31     |
      | 5     | Ford | Supe  | PE       | 2                | 3                     | 1598             | 1990-01-31     |
      | 5     | Ford | Supe  | DI       | 1                | 4                     | 1598             | 2014-01-31     |
    Then the Vehicle Records are Created

  Scenario: Create a Class 7 vehicle record with various transmission types
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with the following data:
      | class | make  | model | fuelType | transmissionType | countryOfRegistration | cylinderCapacity | dateOfFirstUse |
      | 7     | Merce | Anto  | PE       | 1                | 1                     | 1598             | 1990-01-31     |
      | 7     | Merce | Anto  | DI       | 2                | 2                     | 1598             | 2014-01-31     |
      | 7     | Merce | Anto  | PE       | 2                | 3                     | 1598             | 1990-01-31     |
      | 7     | Merce | Anto  | DI       | 1                | 4                     | 1598             | 2014-01-31     |
    Then the Vehicle Records are Created

  Scenario: Create a Class 1 vehicle record
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with the following data:
      | class | make  | model | fuelType | transmissionType | countryOfRegistration | cylinderCapacity | dateOfFirstUse |
      | 1     | Suzuk | Band  | PE       | 1                | 1                     | 1200             | 1990-01-31     |
    Then the Vehicle Records are Created

  Scenario: Create a Class 2 vehicle record
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with the following data:
      | class | make  | model | fuelType | transmissionType | countryOfRegistration | cylinderCapacity | dateOfFirstUse |
      | 2     | Suzuk | Haya  | PE       | 1                | 1                     | 1200             | 1990-01-31     |
    Then the Vehicle Records are Created

  Scenario: Create a Class 3 vehicle record
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with the following data:
      | class | make  | model | fuelType | transmissionType | countryOfRegistration | cylinderCapacity | dateOfFirstUse |
      | 3     | Piagg | MP3   | PE       | 1                | 1                     | 1200             | 1990-01-31     |
    Then the Vehicle Records are Created

  Scenario: Create a Class 4 vehicle record
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with the following data:
      | class | make | model | fuelType | transmissionType | countryOfRegistration | cylinderCapacity | dateOfFirstUse |
      | 4     | BMW  | Mini  | PE       | 1                | 1                     | 1798             | 1990-01-31     |
    Then the Vehicle Records are Created

  Scenario: Create a Class 5 vehicle record
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with the following data:
      | class | make | model | fuelType | transmissionType | countryOfRegistration | cylinderCapacity | dateOfFirstUse |
      | 5     | Ford | Supe  | PE       | 1                | 1                     | 1200             | 1990-01-31     |
    Then the Vehicle Records are Created

  Scenario: Create a Class 7 vehicle record
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with the following data:
      | class | make  | model | fuelType | transmissionType | countryOfRegistration | cylinderCapacity | dateOfFirstUse |
      | 7     | Merce | Anto  | PE       | 1                | 1                     | 5000             | 1990-01-31     |
    Then the Vehicle Records are Created

  Scenario Outline: Create a new vehicle with invalid Class
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with Class of <class>
    Then the Vehicle Record is not Created
    Examples:
      | class |
      | 6     |
      | 8     |
      | -1    |
      | $     |
      | \     |
      | a     |
      | Z     |
      | 99    |

  @defect @vm-4789 @disabled
  Scenario Outline: Create a vehicle record that already exists and the duplicate record is not saved
    Given I am logged in as a Tester
    When I Create a duplicate Vehicle Technical Record with Class of <class>
    Then the Vehicle Record is not Created
    Examples:
      | class |
      | 1     |
      | 2     |
      | 3     |
      | 4     |
      | 5     |
      | 7     |

  Scenario Outline: Create a new vehicle record with a date of first use in the future
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with a date of first use of <dateOfFirstUse>
    Then the Vehicle Record is not Created
    Examples:
      | dateOfFirstUse |
      | 2020-01-31     |
      | 2020-02-31     |

  Scenario Outline: Create a new vehicle record with an invalid date of first use
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with a date of first use of <dateOfFirstUse>
    Then the Vehicle Record is not Created
    Examples:
      | dateOfFirstUse |
      |                |
      | 0000-00-00     |
      | 01-31-xx       |
      | 01-xx-xx       |
      | xx-xx-01       |
      | xx-xx-xx       |
      | -  -  -        |
      | -  -           |
      | -  -           |
      | -              |
      | $$-$$-$$       |

  Scenario: Create Class 4 vehicles registered to a different country
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with the following data:
      | class | make | model | fuelType | transmissionType | countryOfRegistration | cylinderCapacity | dateOfFirstUse |
      | 4     | BMW  | Mini  | PE       | 1                | 1                     | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 2                     | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 3                     | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 4                     | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 5                     | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 6                     | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 7                     | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 8                     | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 9                     | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 10                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 11                    | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 12                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 13                    | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 14                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 15                    | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 16                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 17                    | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 18                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 19                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 20                    | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 21                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 22                    | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 23                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 24                    | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 25                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 26                    | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 27                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 28                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 29                    | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 30                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 31                    | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 32                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 33                    | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 34                    | 1598             | 1990-01-31     |
      | 4     | BMW  | Mini  | DI       | 2                | 35                    | 1598             | 2014-01-31     |
      | 4     | BMW  | Mini  | PE       | 1                | 36                    | 1598             | 1990-01-31     |
    Then the Vehicle Records are Created

  Scenario Outline: Vehicles with decimal cylinder capacity cannot be created
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with cylinder capacity of <cylinderCapacity>
    Then the Vehicle Record is not Created
    Examples:
      | cylinderCapacity |
      | 1.1              |
      | 10.00            |
      | 10.000           |
      | -10.000          |
      | -0               |

  Scenario Outline: Vehicles created
    Given I am logged in as a Tester
    When I Create a new Vehicle Technical Record with Class of <class>
    Then the Vehicle Record is Created
    And the vehicle details are correct
    Examples:
      | class |
      | 1     |
      | 2     |
      | 3     |
      | 4     |
      | 5     |
      | 7     |