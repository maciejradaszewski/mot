@parallel_suite_2
Feature: TQI Tester Performance at AE

  Background:
    Given there is a site associated with Authorised Examiner with following data:
      | siteName         | aeName     | startDate                  |
      | Fast cars garage | Hot Wheels | first day of 15 months ago |
    And There is a tester "John Doe" associated with "Fast cars garage"

  @test-quality-information
  Scenario: User adds risk assessments with earliest date than the date associated to organisation
    Given I am logged in as a Scheme Manager
    When I attempt to add risk assessment to "Fast cars garage" site with data:
      | siteAssessmentScore   | dateOfAssessment           |
      | 100                   | first day of 26 months ago |
      | 20                    | first day of 16 months ago |
    Then being log in as an aedm in "Hot Wheels" I can view authorised examiner statistics with data:
      | siteName         | currentRiskScore | currentAssessmentDate | previousRiskScore | previousAssessmentDate |
      | Fast cars garage |                  |                       |                   |                        |

  @test-quality-information
  Scenario: User adds risk assessments with one earliest date than the date associated to organisation
    Given I am logged in as a Scheme Manager
    When I attempt to add risk assessment to "Fast cars garage" site with data:
      | siteAssessmentScore   | dateOfAssessment           |
      | 100                   | first day of  6 months ago |
      | 20                    | first day of 16 months ago |
    Then being log in as an aedm in "Hot Wheels" I can view authorised examiner statistics with data:
      | siteName         | currentRiskScore | currentAssessmentDate      | previousRiskScore | previousAssessmentDate |
      | Fast cars garage | 100              | first day of  6 months ago |                   |                        |

  @test-quality-information
  Scenario: User adds risk assessments
    Given I am logged in as a Scheme Manager
    When I attempt to add risk assessment to "Fast cars garage" site with data:
      | siteAssessmentScore   | dateOfAssessment           |
      | 100                   | first day of  6 months ago |
      | 20                    | first day of  7 months ago |
    Then being log in as an aedm in "Hot Wheels" I can view authorised examiner statistics with data:
      | siteName         | currentRiskScore | currentAssessmentDate      | previousRiskScore | previousAssessmentDate     |
      | Fast cars garage | 100              | first day of  6 months ago | 20                | first day of  7 months ago |