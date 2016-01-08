package uk.gov.dvsa.ui.feature.journey.mot;

import org.joda.time.DateTime;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.CancelTestReason;
import uk.gov.dvsa.domain.model.mot.TestOutcome;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.mot.*;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.equalToIgnoringCase;
import static org.hamcrest.core.Is.is;

public class ConductMotTests extends BaseTest {

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

    @Test(groups = {"BVT", "Regression"})
    public void passTestSuccessfullyWithNoRFR() throws IOException, URISyntaxException {

        //Given I am on the Test Results Entry Page
        TestResultsEntryPage testResultsEntryPage = pageNavigator.gotoTestResultsEntryPage(tester,vehicle);

        //When I complete all Brake test Values with passing data
        testResultsEntryPage.completeTestDetailsWithPassValues();

        //Then I should see a fail on the test result page
        assertThat(testResultsEntryPage.isPassNoticeDisplayed(), is(true));

        //Then I should be able to complete the Test Successfully
        TestSummaryPage testSummaryPage = testResultsEntryPage.clickReviewTestButton();

        TestCompletePage testCompletePage = testSummaryPage.finishTestAndPrint();

        assertThat(testCompletePage.verifyBackToHomeLinkDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression"} )
    public void startAndAbandonTest() throws URISyntaxException, IOException {

        //Given I start a test and I am on the Test Results Page
        TestResultsEntryPage testResultsEntryPage = pageNavigator.gotoTestResultsEntryPage(tester, vehicle);

        //When I Abandon the test with a reason
        TestAbandonedPage testAbandonedPage =
                testResultsEntryPage.abandonMotTest(CancelTestReason.DANGEROUS_OR_CAUSE_DAMAGE);

        //Then I the test process should be cancelled and a VT30 Certificate generated message is displayed
        assertThat(testAbandonedPage.isVT30messageDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression"} )
    public void startAndAbortTestAsTester() throws URISyntaxException, IOException {

        //Given I start a test and I am on the Test Results Page
        TestResultsEntryPage testResultsEntryPage = pageNavigator.gotoTestResultsEntryPage(tester, vehicle);

        //When I Abort the test with a reason
        TestAbortedPage testAbortedPage = testResultsEntryPage.abortMotTest(CancelTestReason.TEST_EQUIPMENT_ISSUE);

        //Then the test process should be cancelled and a VT30 Certificate generated message is displayed
        assertThat(testAbortedPage.isVT30messageDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression"} )
    public void startAndAbortTestAsVE() throws URISyntaxException, IOException {
        User VehicleExaminer = userData.createVehicleExaminer("Default-VE", false);

        //Given I start a test as Tester
        String testId = motUI.normalTest.startTest();

        //When a Vehicle Examiner abort the test
        motUI.normalTest.viewTestAs(VehicleExaminer, testId);
        motUI.normalTest.abortAsVe();

        //Then the test is aborted successfully
        motUI.normalTest.viewTestAs(VehicleExaminer, testId);
        assertThat(motUI.normalTest.getTestStatus(), equalToIgnoringCase("Aborted by VE"));
    }

    @Test(groups = {"BVT", "Regression"})
    public void conductRetestSuccessfully() throws IOException, URISyntaxException {

        //Given I have a vehicle with a failed MOT test
        motApi.createTest(tester, site.getId(), vehicle, TestOutcome.FAILED, 12345, DateTime.now());

        //And all faults has been fixed

        //When I conduct a retest on the vehicle
        motUI.retest.conductRetestPass(vehicle, tester);

        //Then the retest is successful
        motUI.retest.verifyRetestIsSuccessful();
    }
}
