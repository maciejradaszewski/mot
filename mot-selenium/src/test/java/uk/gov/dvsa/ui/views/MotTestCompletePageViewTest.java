package uk.gov.dvsa.ui.views;

import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.mot.TestCompletePage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.StringContains.containsString;

public class MotTestCompletePageViewTest extends BaseTest {

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

    @Test(groups = {"Regression"}, description = "VM-11255")
    public void verifySuccessfullMessageWhenTestIsPassed() throws IOException, URISyntaxException {

        //Given I complete mot test as a tester
        TestCompletePage testCompletePage = pageNavigator.gotoTestResultsEntryPage(tester,vehicle)
                .completeTestDetailsWithPassValues()
                .clickReviewTestButton()
                .finishTestAndPrint();

        //I can see test summary message with "passed" status
        assertThat(testCompletePage.getTestSummaryMessageText(), containsString("passed"));
    }
}
