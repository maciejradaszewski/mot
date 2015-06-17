Feature: Cherished Transfer
  I want to handle changes to vehicle registration marks
  So that a replacement MOT certificate can be issued for vehicles

  Scenario: Create a replacement MOT certificate for a cherished transfer as an Area Office User
    Given I am logged in as an Area Office User
    When I attempt to create a cherished transfer replacement MOT certificate
    Then a replacement certificate will be created

  Scenario: Attempt to create a replacement MOT certificate as a Tester
    Given I am logged in as a Tester
    When I attempt to create a cherished transfer replacement MOT certificate
    Then I would be forbidden to create replacement