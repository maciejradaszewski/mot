package uk.gov.dvsa.ui.pages.overview;

import org.testng.annotations.BeforeClass;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.Vehicle;
import uk.gov.dvsa.helper.TestDataHelper;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.VehicleSearchPage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.core.Is.is;

public class VehicleSearchPageTest extends BaseTest {

    private User tester;
    private Vehicle vehicle;
    private Site site;
    private AeDetails aeDetails;

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        aeDetails = TestDataHelper.createAe();
        site = TestDataHelper.createSite(aeDetails.getId(), "My_Site");
        tester = TestDataHelper.createTester(site.getId());
        vehicle = TestDataHelper.getNewVehicle();
    }

    @Test (groups = { "BVT" }, description = "VM-9368")
    public void testDisplayedPagesElements() throws IOException, URISyntaxException {

        //Given I'm on vehicle search page
        VehicleSearchPage vehicleSearchPage = pageNavigator().gotoVehicleSearchPage(tester);

        //Assert elements displayed on page
        assertThat("Mot Testing Crumb Trail not displayed properly",
                vehicleSearchPage.getVehicleSearchStepNumber().contains("MOT testing"), is(true));

    }

    @Test (groups = { "BVT" }, description = "VM-9368")
    public void testEmptyRegNumAndEmptyVinValidation() throws IOException, URISyntaxException {

        //Given I'm on vehicle search page
        VehicleSearchPage vehicleSearchPage = pageNavigator().gotoVehicleSearchPage(tester);

        //And search for vehicle with correct registration but no VIN
        vehicleSearchPage.searchVehicle(vehicle.getRegistrationNumber(), "");

        //Assert that create new vehicle info and create new vehicle link is displayed
        verifyNewVehicleInfoAndLinkDisplayed(vehicleSearchPage, true);

        //Assert that no records have been found
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("0 vehicles found"));
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("without a VIN"));

        //And search for vehicle with correct VIN and no registration number
        vehicleSearchPage.searchVehicle("", vehicle.getVin());

        //Assert that create new vehicle info and create new vehicle link is displayed
        verifyNewVehicleInfoAndLinkDisplayed(vehicleSearchPage, true);

        //Assert that no records have been found
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("0 vehicles found"));
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("without a registration"));

        //And search for vehicle with correct 6 last VIN digits and no registration number
        vehicleSearchPage.searchVehicle("", vehicle.getVin().substring(11));

        //Assert that create new vehicle info and create new vehicle link is displayed
        verifyNewVehicleInfoAndLinkDisplayed(vehicleSearchPage, true);

        //Assert that no records have been found
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("0 vehicles found"));
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("without a registration"));
    }

    @Test (groups = { "BVT" }, description = "VM-9368")
    public void testValidRegistrationAndVinNumbers() throws IOException, URISyntaxException {

        //Given I'm on vehicle search page
        VehicleSearchPage vehicleSearchPage = pageNavigator().gotoVehicleSearchPage(tester);

        //And search for vehicle with correct VIN and correct registration number
        vehicleSearchPage.searchVehicle(vehicle.getRegistrationNumber(), vehicle.getVin());

        //Assert that record was found
        assertThat(vehicleSearchPage.isResultVehicleDisplayed(), is(true));
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("1 vehicle found"));
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("with registration " + vehicle.getRegistrationNumber() + " and a VIN matching " + vehicle.getVin() + "."));

        //Assert create new vehicle info and create new vehicle link is displayed
        verifyNewVehicleInfoAndLinkDisplayed(vehicleSearchPage, true);

        //And search for vehicle with correct 6 last VIN digits and correct registration number
        vehicleSearchPage.clickSearchAgain();
        vehicleSearchPage.searchVehicle(vehicle.getRegistrationNumber(), vehicle.getVin().substring(11));

        //Assert that record was found
        assertThat(vehicleSearchPage.isResultVehicleDisplayed(), is(true));
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("1 vehicle found"));
        assertThat(vehicleSearchPage.getMainMessageText(), containsString("with registration " + vehicle.getRegistrationNumber() + " and a VIN ending in " + vehicle.getVin().substring(11) + "."));

        //Assert create new vehicle info and create new vehicle link is displayed
        verifyNewVehicleInfoAndLinkDisplayed(vehicleSearchPage, true);
    }

    private void verifyNewVehicleInfoAndLinkDisplayed(VehicleSearchPage page, boolean value) {
        assertThat(page.isCreateNewVehicleInfoDisplayed(), is(value));
        assertThat(page.isCreateNewVehicleRecordLinkDisplayed(), is(value));
    }
}
