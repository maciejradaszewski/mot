package uk.gov.dvsa.ui.views;

import org.joda.time.DateTime;
import org.testng.annotations.BeforeClass;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.MotTest;
import uk.gov.dvsa.domain.model.mot.TestOutcome;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.mot.TestResultsEntryRetestPage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;

public class TestResultsEntryPageViewTest extends BaseTest {

    private User tester;
    private Vehicle vehicle;
    private Site site;
    private AeDetails aeDetails;
    private MotTest motTest;

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        aeDetails = aeData.createNewAe("Test", 9);
        site = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        tester = userData.createTester(site.getId());
        vehicle = vehicleData.getNewVehicle(tester);
        motTest = motData.createTest(tester, site.getId(), vehicle, TestOutcome.FAILED, 100000, DateTime.now());
    }

    @Test(groups = {"Regression"}, description = "VM-10004")
    public void zeroMilesOdometerSubmitWhenRetestVehicle() throws IOException, URISyntaxException {

        //Given I started retest previous vehicle
        vehicleReinspectionWorkflow().startRetestPreviousVehicle(tester, motTest.getMotTestNumber());

        //When I'm on Test Results Entry page
        TestResultsEntryRetestPage testResultsEntryRetestPage = vehicleReinspectionWorkflow().gotoTestResultsEntryPageWhenRetestStarted();
        //And I update odometer reading with "0" miles
        testResultsEntryRetestPage.fillOdometerReadingAndSubmit(0);

        //Then "0 miles" is displayed and no info about updating odometer is displayed"
        assertThat(testResultsEntryRetestPage.getOdometerReadingText(), containsString("0 km"));
    }

}