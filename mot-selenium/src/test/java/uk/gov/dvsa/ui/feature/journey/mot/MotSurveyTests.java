package uk.gov.dvsa.ui.feature.journey.mot;

import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.DslTest;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.mot.GiveFeedbackPage;
import uk.gov.dvsa.ui.pages.mot.SurveyThankYouPage;
import uk.gov.dvsa.ui.pages.mot.TestCompletePage;

import java.io.IOException;
import java.net.URISyntaxException;

public class MotSurveyTests extends DslTest {

    private Site site;
    private AeDetails aeDetails;
    private User tester;
    private Vehicle vehicle;

    @BeforeMethod(alwaysRun = true)
    private void setupTestData() throws IOException {
        aeDetails = aeData.createAeWithDefaultValues();
        site = siteData.createNewSite(aeDetails.getId(), "TestSite");
        tester = userData.createTester(site.getId());
        vehicle = vehicleData.getNewVehicle(tester);
    }

    @Test(testName = "Survey", groups = {"Regression", "BL-1529"},
          description = "Verifies that user can navigate back from survey page to home page")
    public void backwardNavigationFromSurveyPageToHomePage() throws IOException, URISyntaxException {
        // Given I am on the Test Complete Page as a tester
        TestCompletePage testCompletePage = motUI.normalTest.conductTestPass(tester, vehicle);

        // When I click on the back home link I should be on the Give Feedback Page
        GiveFeedbackPage giveFeedbackPage = testCompletePage.clickBackHomeLink();

        // When I click on Cancel and return home link
        giveFeedbackPage.clickCancelAndReturnHome();

        // Then I should be redirected to the home page

    }

    @Test(testName = "Survey", groups = {"Regression", "BL-1529"},
            description = "Verifies that user is redirected to home page after submitting empty survey")
    public void userRedirectedToHomePageOnSubmissionOfEmptySurvey() throws IOException, URISyntaxException {
        // Given I am on the Test Complete Page as a tester
        TestCompletePage testCompletePage = motUI.normalTest.conductTestPass(tester, vehicle);

        // When I click on the back home link I should be on the Give Feedback Page
        GiveFeedbackPage giveFeedbackPage = testCompletePage.clickBackHomeLink();

        // When I submit an empty survey
        giveFeedbackPage.submitFeedback(true, HomePage.class);

        // Then I should be redirected to the home page

    }

    @Test(testName = "Survey", groups = {"Regression", "BL-1529"},
            description = "Verifies that user is able to submit feedback")
    public void userCanSubmitFeedback() throws IOException, URISyntaxException {
        // Given I am on the Test Complete Page as a tester
        TestCompletePage testCompletePage = motUI.normalTest.conductTestPass(tester, vehicle);

        // When I click on the back home link I should be on the Give Feedback Page
        GiveFeedbackPage giveFeedbackPage = testCompletePage.clickBackHomeLink();

        // When I submit some feedback and click on the Back Home link
        giveFeedbackPage.submitFeedback(false, SurveyThankYouPage.class)
                .clickBackHomeLink();

        // Then I should be redirected to the Home page

    }
}
