Feature: Vehicle Search
  As a Tester
  I want to be able to search for a vehicle
  So that I can perform MOT tests successfully

  Scenario: Tester performs vehicle Search
    Given I am logged in as a Tester
    When I search for a vehicle by registration number "LOL999"
    Then the vehicle registration number "LOL999" is returned

  Scenario Outline: Tester performs vehicle search and includes white spaces in VRM and VIN
    Given I am logged in as a Tester
    And a vehicle with registration number "<registration number>" and VIN "<VIN>" exists
    When I search for a vehicle by registration number "<search registration number>" and VIN "<search VIN>"
    Then the vehicle with "<registration number>" and VIN "<VIN>" is found
  Examples:
  | registration number | VIN                                 |search registration number | search VIN           |
  | FNZ6110             | 1M8GDM9AXKP042788                   | F N Z 6 1 1   0           | 1M8GDM9AXKP042788    |
  | FNZ6110             | 1M8GDM9AXKP042788                   |  FNZ 6110                 | 1 M 8 GDM9AXKP042788 |
  | FNZ6110             | 1M8GDM9AXKP042788                   | FNZ  6 110                | 0 4 2 7 8 8          |
  | FNZ6110             | 1M8GDM9AXKP042788                   | FNZ6110                   | 1M8GDM 9 A XKP042788 |
  | FNZ6110             | 1M8GDM9AXKP042788                   | FNZ6110                   | 0 4 27 8 8           |


