Feature: Initiate Refund
  As a finance user
  I want to ask for a refund of slots purchase with one or more payments
  So that slots balances can be reduced if a payment fails or a garage closes down

  @slots
  Scenario Outline: Initiate a refund as a finance user
    Given I am logged in as a Finance User
    And I bought <slots> slots for organisation <organisation> at <price> price
    And I bought <slots> slots for organisation <organisation> at <price> price
    When I request a refund of <slotsToRefund> slots for organisation <organisation>
    Then The slots purchased should be refunded
  Examples:
    | slots | organisation | price | slotsToRefund |
    | 100   | halfords     | 2.05  | 10            |
    | 307   | halfords     | 2.05  | 307           |
    | 90    | halfords     | 2.05  | 150           |
    | 120   | halfords     | 2.05  | 240           |

  @slots
  Scenario Outline: Retrieve Refund Summary Information
    Given I am logged in as a Finance User
    And I bought <slots> slots for organisation <organisation> at <price> price
    When I ask for refund summary of <slotsToRefund> slots for organisation <organisation>
    Then I should receive summary information
  Examples:
    | slots | organisation | price | slotsToRefund |
    | 100   | halfords     | 2.05  | 45            |
    | 10    | halfords     | 2.05  | 5             |
    | 45    | halfords     | 2.05  | 45            |
    | 200   | halfords     | 2.05  | 1             |

  @slots
  Scenario Outline: Finance user attempts to refund slots more than the total slot balance
    Given I am logged in as a Finance User
    And I bought <slots> slots for organisation <organisation> at <price> price
    When I request a refund of <slotsToRefund> slots for organisation <organisation>
    Then My refund request should be rejected
  Examples:
    | slots | organisation | price | slotsToRefund |
    | 100   | kwikfit      | 2.05  | 15000         |
    | 89    | kwikfit      | 2.05  | 75000         |

  @slots
  Scenario Outline: No other user is authorised to initiate a refund
    Given I am authenticated as <username>
    When I request a refund of <slotsToRefund> slots for organisation <organisation>
    Then My refund request should be rejected
  Examples:
    | username        | organisation | slotsToRefund |
    | areaoffice1user | halfords     | 45            |
    | ae              | halfords     | 45            |
    | areaadmin       | halfords     | 45            |
    | schememgt       | halfords     | 45            |
    | aedm            | halfords     | 45            |
    | inactivetester  | halfords     | 45            |
    | csco            | halfords     | 45            |
    | aed1            | halfords     | 45            |

  @slots
  Scenario: Finance user attempts to refund zero slots
    Given I am logged in as a Finance User
    When I request a refund of "0" slots for organisation "halfords"
    Then My refund request should be rejected

  @slots
  Scenario Outline: Refund slots when the latest transaction is reversed
    Given I am logged in as a Finance User
    And I bought <slots> slots for organisation <organisation> at <price> price
    And I bought <slots> slots for organisation <organisation> at <price> price
    And The latest transaction is reversed
    When I request a refund of <slotsToRefund> slots for organisation <organisation>
    Then The slots purchased should be refunded
  Examples:
    | slots | organisation | price | slotsToRefund |
    | 1000  | kwikfit      | 2.05  | 100           |
    | 500   | kwikfit      | 2.05  | 500           |


