package uk.gov.dvsa.ui.views;

import org.testng.annotations.BeforeClass;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.VehicleSearchPage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.core.Is.is;

public class VehicleSearchViewTest extends BaseTest {

    private User tester;
    private Vehicle vehicle;

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        Site site = siteData.createNewSite(aeDetails.getId(), "My_Site");
        tester = userData.createTester(site.getId());
        vehicle = vehicleData.getNewVehicle(tester);
    }

    @Test(groups = {"Regression"}, description = "VM-9368")
    public void breadCrumbTrailIsDisplayed() throws IOException, URISyntaxException {

        //Given I'm on the vehicle search page
        VehicleSearchPage vehicleSearchPage = pageNavigator.gotoVehicleSearchPage(tester);

        //I Expect the BreadCrumb to contain Mot Testing
        assertThat("Mot Testing Crumb Trail not displayed properly",
                vehicleSearchPage.getVehicleSearchStepNumber().contains("MOT testing"), is(true));

    }

    @Test(groups = {"Regression"}, description = "VM-9368")
    public void validationMessageIsDisplayedForInvalidRegOrVin() throws IOException, URISyntaxException {

        //Given I'm on vehicle search page
        VehicleSearchPage vehicleSearchPage = pageNavigator.gotoVehicleSearchPage(tester);

        //When I search for vehicle without registration and VIN
        vehicleSearchPage.searchVehicle("", "");

        //Then I should see the Validation Message
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("0 vehicles found"));
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("without a VIN"));
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("without a registration"));

        //And the create new vehicle link is displayed
        verifyNewVehicleInfoAndLinkDisplayed(vehicleSearchPage, true);
    }

    @Test(groups = {"BVT", "Regression"}, description = "VM-9368")
    public void validRegAndVinReturnsCorrectVehicle() throws IOException, URISyntaxException {

        //Given I'm on vehicle search page
        VehicleSearchPage vehicleSearchPage = pageNavigator.gotoVehicleSearchPage(tester);

        //When I search for a vehicle with correct VIN and registration number
        vehicleSearchPage.searchVehicle(vehicle.getRegistrationNumber(), vehicle.getVin());

        //Then the vehicle is found record was found
        assertThat(vehicleSearchPage.isResultVehicleDisplayed(), is(true));
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("with registration " +
                vehicle.getRegistrationNumber() + " and a VIN matching " + vehicle.getVin() + "."));
    }

    private void verifyNewVehicleInfoAndLinkDisplayed(VehicleSearchPage page, boolean value) {
        assertThat(page.isCreateNewVehicleInfoDisplayed(), is(value));
        assertThat(page.isCreateNewVehicleRecordLinkDisplayed(), is(value));
    }
}
