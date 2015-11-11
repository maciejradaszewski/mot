package uk.gov.dvsa.ui.feature.journey.mot_test;

import org.joda.time.DateTime;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.DateRange;
import uk.gov.dvsa.domain.model.mot.TestOutcome;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.BaseTest;

import uk.gov.dvsa.ui.pages.VehicleSearchPage;
import uk.gov.dvsa.ui.pages.mot.*;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class ContingencyMotTests extends BaseTest {
    private User tester;
    private Vehicle vehicle;
    private final String contingencyCode = "12345A";
    private AeDetails aeDetails;
    private Site site;
    private User siteManager;

    @BeforeMethod(alwaysRun = true)
    private void setup() throws IOException {
        aeDetails = aeData.createAeWithDefaultValues();
        site = siteData.createNewSite(aeDetails.getId(), "New_vts");
        tester = userData.createTester(site.getId());
        siteManager = userData.createSiteManager(site.getId(), true);
        vehicle = vehicleData.getNewVehicle(tester);
    }

    @Test(groups = {"BVT", "Regression", "VM-4825,Sprint05,VM-9444 Regression"})
    public void recordContingencyTestSuccessfully() throws IOException, URISyntaxException {
        //Given I am the Record Contingency Page
        motUI.contingency.testPage(tester);

        //When I enter valid Contingency Test details
        motUI.contingency.enterTest(contingencyCode, DateTime.now(), vehicle);

        //Then it Contingency is entered successfully
        assertThat(motUI.contingency.isTestSaveSuccessful(), is(true));
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

    @Test
    public void endOfContingencyDeclarationStatement() throws IOException, URISyntaxException {

        //Given I am an Mot tester
        User tester = userData.createTester(1);
        Vehicle vehicle = vehicleData.getNewVehicle(tester);

        //When I complete a contingency test and view the summary page
        motUI.contingencyTest.conductContingencyTest(tester, vehicle);

        //Then I should be presented with the declaration statement
        assertThat(motUI.contingencyTest.isDeclarationStatementDisplayed(), is(true));
    }
}
