package uk.gov.dvsa.ui.feature.journey;

import org.joda.time.DateTime;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.TestOutcome;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.VehicleSearchPage;
import uk.gov.dvsa.ui.pages.mot.*;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class ContingencyMotTest extends BaseTest {
    private User tester;
    private Vehicle vehicle;
    private final String contingencyCode = "12345A";
    private AeDetails aeDetails;
    private Site site;

    @BeforeMethod(alwaysRun = true)
    private void setup() throws IOException {
        aeDetails = aeData.createAeWithDefaultValues();
        site = siteData.createNewSite(aeDetails.getId(), "New_vts");
        tester = userData.createTester(site.getId());
        vehicle = vehicleData.getNewVehicle(tester);
    }

    @Test(groups = {"BVT", "Regression", "VM-4825,Sprint05,VM-9444 Regression"})
    public void conductTestSuccessfully() throws IOException, URISyntaxException {

        //Given I am on the Test Contingency Test Entry page
        ContingencyTestEntryPage contingencyTestEntryPage = pageNavigator.gotoContingencyTestEntryPage(tester);

        //When I complete contingency test form and provide the contingency code
        VehicleSearchPage vehicleSearchPage = contingencyTestEntryPage.
                fillContingencyTestFormAndConfirm(contingencyCode, DateTime.now());

        //I can proceed with the Mot test
        StartTestConfirmationPage startTestConfirmationPage =
                vehicleSearchPage.searchVehicle(vehicle).selectVehicleForTest();

        TestResultsEntryPage testResultsEntryPage =
                startTestConfirmationPage.clickStartMotTestWhenConductingContingencyTest();

        //And when I complete all Brake test Values with passing data
        testResultsEntryPage.completeTestDetailsWithPassValues();

        //Then I should see a fail on the test result page
        assertThat(testResultsEntryPage.isPassNoticeDisplayed(), is(true));

        //Then I should be able to complete the Test Successfully
        TestSummaryPage testSummaryPage = testResultsEntryPage.clickReviewTestButton();

        TestCompletePage testCompletePage = testSummaryPage.finishTestAndPrint();

        assertThat(testCompletePage.verifyBackToHomeLinkDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "VM-4825,Sprint05,VM-9444 Regression"})
    public void conductReTestSuccessfully() throws IOException, URISyntaxException {

        //Given I have a vehicle with a failed MOT test
        motApi.createTest(tester, site.getId(), vehicle, TestOutcome.FAILED, 12345, DateTime.now());

        //And all faults has been fixed

        //When I Conduct a re-test on the vehicle via contingency route
        motUI.retest.conductContingencyRetest(tester, contingencyCode, vehicle);

        //Then the retest is successful
        motUI.retest.verifyRetestIsSuccessful();
    }
}
