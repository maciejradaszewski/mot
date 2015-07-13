package uk.gov.dvsa.ui.user.journeys;

import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.*;
import uk.gov.dvsa.helper.TestDataHelper;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.*;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class ConductMotTestAsTester extends BaseTest {

    @DataProvider(name = "TesterAndVehicle")
    public Object[][] createTesterAndVehicle() throws IOException {
        AeDetails aeDetails = TestDataHelper.createAe();
        Site testSite = TestDataHelper.createSite(aeDetails.getId(), "My_Site");
        User tester = TestDataHelper.createTester(testSite.getId());
        Vehicle vehicle = TestDataHelper.getNewVehicle();

        return new Object[][]{{tester, vehicle}};
    }

    @Test(groups = {"BVT"}, dataProvider = "TesterAndVehicle")
    public void passTestSuccessfullyWithNoRFR(User tester, Vehicle vehicle) throws IOException, URISyntaxException {

        //Given I am on the Test Results Entry Page
        TestResultsEntryPage testResultsEntryPage = pageNavigator().gotoTestResultsEntryPage(tester,vehicle);

        //When I complete all Brake test Values with passing data
        testResultsEntryPage.completeTestDetailsWithPassValues();

        //Then I should see a fail on the test result page
        assertThat(testResultsEntryPage.isPassNoticeDisplayed(), is(true));

        //Then I should be able to complete the Test Successfully
        TestSummaryPage testSummaryPage = testResultsEntryPage.clickReviewTestButton();

        TestCompletePage testCompletePage = testSummaryPage.finishTestAndPrint();

        assertThat(testCompletePage.verifyPrintButtonDisplayed(), is(true));
    }

    @Test(groups = {"BVT"}, dataProvider = "TesterAndVehicle")
    public void failTestSuccessfullyWithRFR(User tester, Vehicle vehicle) throws URISyntaxException, IOException {

        //Given I am at the Test Result Page
        TestResultsEntryPage testResultsEntryPage = pageNavigator().gotoTestResultsEntryPage(tester, vehicle);

        //When I complete all brake test values with falling data
        testResultsEntryPage.completeTestDetailsWithFailValues();

        //Then I should see a fail on the test result page
        assertThat(testResultsEntryPage.isFailedNoticeDisplayed(), is(true));

        //And when I add RFR, Advisory and PRS
        TestSummaryPage testSummaryPage = testResultsEntryPage.addDefaultRfrPrsAndManualAdvisory();

        //Then I should be able to complete the test
        TestCompletePage testCompletePage = testSummaryPage.finishTestAndPrint();

        assertThat(testCompletePage.isRefusalMessageDisplayed(), is(true));
    }

    @Test(groups = {"BVT"}, dataProvider = "TesterAndVehicle")
    public void startAndAbandonTest(User tester, Vehicle vehicle) throws URISyntaxException, IOException {

        //Given I start a test and I am on the Test Results Page
        TestResultsEntryPage testResultsEntryPage = pageNavigator().gotoTestResultsEntryPage(tester, vehicle);

        //When I Abandon the test with a reason
        TestAbandonedPage testAbandonedPage =
                testResultsEntryPage.abandonMotTest(CancelTestReason.DANGEROUS_OR_CAUSE_DAMAGE);

        //Then I the test process should be cancelled and a VT30 Certificate generated message is displayed
        assertThat(testAbandonedPage.isVT30messageDisplayed(), is(true));
    }

    @Test(groups = {"BVT"}, dataProvider = "TesterAndVehicle")
    public void startAndAbortTest(User tester, Vehicle vehicle) throws URISyntaxException, IOException {

        //Given I start a test and I am on the Test Results Page
        TestResultsEntryPage testResultsEntryPage = pageNavigator().gotoTestResultsEntryPage(tester, vehicle);

        //When I Abort the test with a reason
        TestAbortedPage testAbortedPage = testResultsEntryPage.abortMotTest(CancelTestReason.TEST_EQUIPMENT_ISSUE);

        //Then the test process should be cancelled and a VT30 Certificate generated message is displayed
        assertThat(testAbortedPage.isVT30messageDisplayed(), is(true));
    }
}
