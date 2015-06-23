package com.dvsa.mot.selenium.e2e.dvsa;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.MotTestApi;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.RetestSummaryPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SearchVehicleInformationPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.VehicleDetailsPage;
import org.joda.time.DateTime;
import org.testng.Assert;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

// AO should be able to search for a vehicle, then view technical records of the vehicle,
// then navigate to MOT test history and view test.


public class AreaOfficerVehicleInformationSearchTest extends BaseTest {


    /* the tests are disabled for now & there is a ticket VM-7866 been raised for the permission issue */
    @Test(groups = {"VM-4184", "VM-4186", "VM-7866", "Sprint2b-V", "E2E", "VM-7274", "Regression"},
            description = "Verify vehicle details for a single VRM or VIN search")
    public void verifyVehicleDetailsForSingleVehicleSearch() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002);

        createMotTest(login, Site.POPULAR_GARAGES, vehicle, 13345, MotTestApi.TestOutcome.FAILED,
                new DateTime().minusDays(2));

        VehicleDetailsPage detailsPage = SearchVehicleInformationPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1).verifyPageTitle()
                .selectVehicleInfoType(Text.TEXT_VRM_TYPE).enterSearchTerm(vehicle.carReg)
                .clickSingleVehicleSearch();
        detailsPage.verifyPageElements().clickHistoryLink().clickSummaryLink();
        RetestSummaryPage retestSummaryPage = new RetestSummaryPage(driver);
        Assert.assertFalse(retestSummaryPage.verifyMotTestTypeDropDownBox());
        Assert.assertFalse(retestSummaryPage.verifyStartInspectionButton());
    }


    @DataProvider(name = "MultipleVehicleMOTTests") public Object[][] MOTMultipleVehicles() {
        return new Object[][] {{Login.LOGIN_AREA_OFFICE1, Text.TEXT_VRM_TYPE,
                Vehicle.VEHICLE_MULTIPLE_VALID_VRM.carReg, Vehicle.VEHICLE_MULTIPLE_VALID_VRM},
                {Login.LOGIN_AREA_OFFICE1, Text.TEXT_VIN_TYPE,
                        Vehicle.VEHICLE_MULTIPLE_VALID_VIN.fullVIN,
                        Vehicle.VEHICLE_MULTIPLE_VALID_VIN}};
    }

    /* the tests are disabled for now & there is a ticket VM-7866 been raised for the permission issue */
    @Test(groups = {"VM-4186", "Sprint2b-V", "E2E", "VM-7866", "VM-7274", "Regression"},
            dataProvider = "MultipleVehicleMOTTests",
            description = "Verify MOT test history for multiple vehicle search")
    public void verifyMOTTestHistoryForMultipleVehicleSearch(Login login2, String type,
            String searchTerm, Vehicle vehicle) {

        createMotTest(login, Site.POPULAR_GARAGES, Vehicle.VEHICLE_MULTIPLE_VALID_VRM, 13345,
                MotTestApi.TestOutcome.FAILED, new DateTime().minusDays(4));

        createMotTest(login, Site.POPULAR_GARAGES, Vehicle.VEHICLE_MULTIPLE_VALID_VIN, 13345,
                MotTestApi.TestOutcome.FAILED, new DateTime().minusDays(5));

        SearchVehicleInformationPage.navigateHereFromLoginPage(driver, login2).verifyPageTitle()
                .selectVehicleInfoType(type).enterSearchTerm(searchTerm).clickMultipleSearch()
                .enterFilterText(vehicle.carReg).clickDetailsLink().clickHistoryLink()
                .clickSummaryLink().verifyReInspectionForAreaAdmin();
    }

}
