package uk.gov.dvsa.ui.views;

import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.vehicleinformation.VehicleInformationResultsPage;
import uk.gov.dvsa.ui.pages.vehicleinformation.VehicleInformationSearchPage;

import java.io.IOException;

public class VehicleInformationViewTest extends BaseTest {

    @Test (groups = {"BVT", "Regression"})
    public void viewVehicleInformationSuccessfully() throws IOException{
        User areaOffice1User = new User("areaOffice1User", "Password1");
        Vehicle vehicle = vehicleData.getNewVehicle(userData.createTester(1));

        //Given i am on the Vehicle Information Page as an AreaOffice1User
        VehicleInformationSearchPage vehicleInformationSearchPage =
                pageNavigator.goToVehicleInformationSearchPage(areaOffice1User);

        //When i successfully find a vehicle
        VehicleInformationResultsPage vehicleInformationResultsPage = vehicleInformationSearchPage
                .searchAndFindVehicleByRegistrationSuccessfully(vehicle.getRegistrationNumber());

        //Then i should be able to view that vehicles information
        vehicleInformationResultsPage
                .clickVehicleDetailsLink()
                .verifyVehicleRegistrationAndVin(vehicle);
    }
}
