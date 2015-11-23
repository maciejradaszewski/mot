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

public class VehicleInformationViewTests extends BaseTest {
    private User tester;
    private Vehicle vehicle;

    @BeforeMethod(alwaysRun = true)
    public void setUp() throws IOException {
        Site site = siteData.createSite();
        tester = userData.createTester(site.getId());
    }

    @Test (groups = {"BVT", "Regression"})
    public void viewVehicleInformationSuccessfully() throws IOException{
        User areaOffice1User = new User("areaOffice1User", "Password1");
        Vehicle vehicle = vehicleData.getNewVehicle(userData.createTester(1));

        //Given I am on the Vehicle Information Page as an AreaOffice1User
        VehicleInformationSearchPage vehicleInformationSearchPage =
                pageNavigator.goToVehicleInformationSearchPage(areaOffice1User);

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
        motUI.startTestConfirmationPage(tester, vehicle);

        //Then I want to see the vehicle's weight
        motUI.isTextPresent("1250 kg");
    }

    @Test(groups = {"BVT", "Regression"}, description = "BL-46")
    public void displayUnknownForVehicleWithNoWeightInStartTestConfirmationPage() throws IOException, URISyntaxException {
        //Given I have a vehicle with no registered weight
        vehicle = vehicleData.getNewVehicle(tester);

        //When I search for the vehicle to perform a test on it
        motUI.startTestConfirmationPage(tester, vehicle);

        //Then I should see its weight displayed as "Unknown"
        motUI.isTextPresent("Unknown");
    }
}
