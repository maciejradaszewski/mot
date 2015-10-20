Feature: Vehicle Search
  As a Tester
  I want to be able to search for a vehicle
  So that I can perform MOT tests successfully

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

  Scenario Outline: Tester performs vehicle search and mistypes vehicle registration or VIN
    Given I am logged in as a Tester
    And a vehicle with registration number "<registration number>" and VIN "<VIN>" exists
    When I search for a vehicle by registration number "<search registration number>" and VIN "<search VIN>"
    Then the vehicle with "<registration number>" and VIN "<VIN>" is found
  Examples:
  | registration number | VIN                                 |search registration number | search VIN               |
  | FNZ6110             | 1M8GDM9AXKP042788                   | FN26110                   | 1M86DM94XKP042788        |
  | GGG455              | 1M8GDM91M6GDM9AXKP042766            | 6GG455                    | 1M86DM91M66DM94XKP042766 |
  | HI3110              | 1M4GDM9AXKP042744                   | H13110                    | 1M46DM94XKP042744        |
  | CRZ4545             | 1M2GDM9AXKP042722                   | CR24545                   | 1M26DM94XKP042722        |
  | AAI8845             | 1M1GDM9AXKP042711                   | A418845                   | 1M16DM94XKP042711        |
  | GO4501              | 1M5GDM9AXKP042714                   | 604501                    | 1M56DM94XKP042714        |
  | RIL8080             | 4S4BP67CX45450431                   | R178080                   | 4548P67CX45450431        |
  | XIM7100             | 1AA0020CHAR00VINGDM1                | X1M7100                   | 1440020CH4R00V1N6DM1     |
  | VK02MOT             | WV1ZZZ8ZH6H091596                   | VK02M07                   | WV122282H6H091596        |
  | IM04NI              | JKBZXNC11AA021638                   | 1M04N1                    | JK82XNC1144021638        |
  | SM17HH              | WDB20202221F26807                   | 5M17HH                    | WD820202221F26807        |
  | SSI29MAR            | 1HD1PDK10DY936456                   | 55129M4R                  | 1HD1PDK10DY936456        |
  | DII4454             | 1M8GDM9AXKP042788                   | D1I4454                   | 1M86DM94XKP042788        |
  | KIK2111             | 1M8GDM9AXKP042788                   | K1K2111                   | 1M86DM94XKP042788        |
  | HI3110              | 1M8GDM9AXKP042788                   | H13110                    | 1M86DM94XKP042788        |
  | FO036               | 1M8GDM9AXKP042788                   | F0036                     | 1M86DM94XKP042788        |