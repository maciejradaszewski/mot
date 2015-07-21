Feature: Manual Adjustment of transaction
  As a Finance User
  I want to make adjustments to existing slot transactions
  When a payment previously input by Finance is discovered to contain an error
  So that the right customer gets the right payment, product or amount

  @slots
  Scenario Outline: Manual Adjustment when a transaction is allocated to an Incorrect Authorised Examiner
    Given I am logged in as a Finance User
    And A slot transaction exist
    When I adjust the transaction to the correct Authorised Examiner <authorisedExaminer>
    Then The transaction should be adjusted
  Examples:
    | authorisedExaminer |
    | kwikfit            |
    | asda               |

  @slots
  Scenario Outline: Manual Adjustment when a transaction is created with Incorrect Data
    Given I am logged in as a Finance User
    And A slot transaction exist
    When I adjust the transaction attribute <field> to <value> because of <reason>
    Then The transaction should be adjusted
  Examples:
    | field        | value    | reason    |
    | chequeNumber | 89076834 | wrongData |
    | slipNumber   | 1019183  | wrongData |
    | accountName  | James    | wrongData |
    | amount       | 410.00   | wrongData |