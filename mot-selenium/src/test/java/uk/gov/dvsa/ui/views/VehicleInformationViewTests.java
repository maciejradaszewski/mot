package uk.gov.dvsa.ui.views;

import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.vehicleinformation.VehicleInformationResultsPage;
import uk.gov.dvsa.ui.pages.vehicleinformation.VehicleInformationSearchPage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class VehicleInformationViewTests extends BaseTest {
    private User tester;
    private Vehicle vehicle;

    @BeforeMethod(alwaysRun = true)
    public void setUp() throws IOException {
        Site site = siteData.createSite();
        tester = userData.createTester(site.getId());
        vehicle = vehicleData.getNewVehicle(tester);
    }

    @Test (groups = {"BVT", "Regression"})
    public void viewVehicleInformationSuccessfully() throws IOException, URISyntaxException {
        User areaOffice1User = new User("areaOffice1User", "Password1");

        //Given i am on the Vehicle Information Page as an AreaOffice1User
        VehicleInformationSearchPage vehicleInformationSearchPage =
                pageNavigator.navigateToPage(areaOffice1User, VehicleInformationSearchPage.PATH, VehicleInformationSearchPage.class);

        //When I search for a vehicle
        VehicleInformationResultsPage vehicleInformationResultsPage = vehicleInformationSearchPage
                .searchAndFindVehicleByRegistrationSuccessfully(vehicle.getRegistrationNumber());

        //Then i should be able to view that vehicles information
        vehicleInformationResultsPage
                .clickVehicleDetailsLink()
                .verifyVehicleRegistrationAndVin(vehicle);
    }

    @Test(groups = {"BVT", "Regression"}, description = "BL-46")
    public void vehicleWeightShownInStartTestConfirmationPage() throws IOException, URISyntaxException {
        //Given I have a vehicle with registered weight
        vehicle = vehicleData.getNewVehicle(tester, 1250);

        //When I search for the vehicle to perform a test on it
        motUI.normalTest.startTestConfirmationPage(tester, vehicle);

        //Then I want to see the vehicle's weight
        motUI.normalTest.isTextPresent("1250 kg");
    }

    @Test(groups = {"BVT", "Regression"}, description = "BL-46")
    public void displayUnknownForVehicleWithNoWeightInStartTestConfirmationPage() throws IOException, URISyntaxException {

        //Given I have a vehicle with no registered weight

        //When I search for the vehicle to perform a test on it
        motUI.normalTest.startTestConfirmationPage(tester, vehicle);

        //Then I should see its weight displayed as "Unknown"
        motUI.normalTest.isTextPresent("Unknown");
    }

    @Test(groups = {"BVT", "Regression"})
    public void editVehicleClass() throws IOException, URISyntaxException {

        //Given I am on the StartTestConfirmation Page
        motUI.normalTest.startTestConfirmationPage(tester, vehicle);

        //When I edit the vehicle class
        motUI.normalTest.changeClass("5");

        //Then I submit the new class successfully
        assertThat(motUI.normalTest.isDeclarationStatementDisplayed(), is(true));
    }
}
