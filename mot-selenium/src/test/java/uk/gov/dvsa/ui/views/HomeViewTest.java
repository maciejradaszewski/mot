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
import uk.gov.dvsa.ui.pages.HomePage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;

public class HomeViewTest extends BaseTest {

    private User tester;
    private Vehicle vehicle;
    private Site site;
    private AeDetails aeDetails;
    private MotTest motTest;

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        AeDetails aeDetails = aeData.createNewAe("Test", 7);
        Site site = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        tester = userData.createTester(site.getId());
        vehicle = vehicleData.getNewVehicle(tester);
        motTest = motData.createTest(tester, site.getId(), vehicle, TestOutcome.FAILED, 1234, DateTime.now());
    }

    @Test (groups = { "Regression" }, description = "VM-10981")
    public void resumeMotTestButtonChangedToEnterRetestResultsWhenRetestVehicle() throws IOException, URISyntaxException {

        //Given that I am logged in as a tester and I start retest previous vehicle
        HomePage homePage = vehicleReinspectionWorkflow()
                .startRetestPreviousVehicle(tester, motTest.getMotTestNumber());

        //Then on Home page I can see button "Enter retest results"
        assertThat(homePage.getResumeMotTestButtonText(), containsString("Enter retest results"));

    }
}
