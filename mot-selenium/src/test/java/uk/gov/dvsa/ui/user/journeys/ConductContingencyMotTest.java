package uk.gov.dvsa.ui.user.journeys;

import org.joda.time.DateTime;
import org.testng.annotations.BeforeClass;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.Vehicle;
import uk.gov.dvsa.domain.service.ServiceLocator;
import uk.gov.dvsa.helper.TestDataHelper;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.*;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class ConductContingencyMotTest extends BaseTest {
    private User tester;
    private Vehicle vehicle;
    private Site testSite;
    private AeDetails aeDetails;
    private static final String contingencyCode = "12345A";

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        aeDetails = ServiceLocator.getAeService().createAe("New_Ae", 7);
        testSite = ServiceLocator.getSiteService().createSite(aeDetails.getId(), "New_vts");
        tester = ServiceLocator.getUserService().createUserAsTester(testSite.getId());
        vehicle = TestDataHelper.getNewVehicle();
    }

    @Test(groups = {"BVT"})
    public void conductContingencyMotTestSuccessfull() throws IOException, URISyntaxException {

        //Given I am on the Test Contingency Test Entry page
        ContingencyTestEntryPage contingencyTestEntryPage = pageNavigator().gotoContingencyTestEntryPage(tester);

        //When I complete contingency test form and provide the contingency code
        VehicleSearchPage vehicleSearchPage = contingencyTestEntryPage.fillContingencyTestFormAndConfirm(contingencyCode, new DateTime().now());

        //I can proceed with the Mot test
        StartTestConfirmationPage startTestConfirmationPage = vehicleSearchPage.searchVehicle(vehicle).selectVehicleFromTable();
        TestResultsEntryPage testResultsEntryPage = startTestConfirmationPage.clickStartMotTestWhenConductingContingencyTest();

        //And when I complete all Brake test Values with passing data
        testResultsEntryPage.completeTestDetailsWithPassValues();

        //Then I should see a fail on the test result page
        assertThat(testResultsEntryPage.isPassNoticeDisplayed(), is(true));

        //Then I should be able to complete the Test Successfully
        TestSummaryPage testSummaryPage = testResultsEntryPage.clickReviewTestButton();

        TestCompletePage testCompletePage = testSummaryPage.finishTestAndPrint();

        assertThat(testCompletePage.verifyPrintButtonDisplayed(), is(true));
    }
}
