package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.ReasonForRefusal;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestRefusedPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.RefuseToTestPage;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class ReasonsForTestRefusalTest extends BaseTest {

    @DataProvider(name = "ReasonsForRefusal") public Object[][] reasonsForRefusal() {

        return new Object[][] {{createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002),
                ReasonForRefusal.UNABLE_IDENTIFY_DATE_FIRST_USE},
                {createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002),
                        ReasonForRefusal.VEHICLE_TOO_DIRTY_TO_EXAMINE},
                {createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002),
                        ReasonForRefusal.VEHICLE_IS_NOT_FIT_TO_BE_DRIVEN},
                {createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011),
                        ReasonForRefusal.INSECURITY_OF_LOAD},
                {createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011),
                        ReasonForRefusal.VEHICLE_CONFIG_SIZE_UNSUITABLE},
                {createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011),
                        ReasonForRefusal.VEHICLE_EMITS_SUBSTANCIAL_SMOKE},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        ReasonForRefusal.UNABLE_TO_OPEN_DEVICE},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        ReasonForRefusal.INSPECTION_MAY_BE_DANGEROUS},
                {createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924),
                        ReasonForRefusal.REQUESTED_TEST_FEE_NOT_PAID},
                {createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924),
                        ReasonForRefusal.SUSPECT_MAINTENANCE_HISTORY_OF_DIESEL_ENGINE},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        ReasonForRefusal.MOTORCYCLE_FRAME_STAMPED_NOT_FOR_ROAD},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        ReasonForRefusal.VTS_NOT_AUTHORISED_TO_TEST_VEHICLE_CLASS}};
    }

    @Test(dataProvider = "ReasonsForRefusal", groups = {"VM-5017", "Regression", "short"})
    public void testAllReasonsForRefusal(Vehicle vehicle, ReasonForRefusal reasonForRefusal) {

        MotTestRefusedPage motTestRefusedPage = MotTestRefusedPage
                .navigateHereFromLoginPage(driver, login, vehicle, reasonForRefusal);

        assertThat("Check that the print document button is displayed after MOT test is refused",
                motTestRefusedPage.isPrintDocumentButtonDisplayed(), is(true));

        motTestRefusedPage.clickLogout();
    }

    @Test(groups = {"VM-5017", "Regression", "W-Sprint6"})
    public void testErrorMessageWhenNoReasonForRefusalIsSelected() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);

        RefuseToTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .clickRefuseMotTestExpectingError();

        assertThat(
                "Check that an error message is displayed when reason for refusal is not selected",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
    }

    @Test(groups = {"VM-5017", "Regression", "W-Sprint6"})
    public void testCheckVehicleDetailsAndBackToSearchFromRefuseToTestPage() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);

        RefuseToTestPage refuseToTestPage =
                RefuseToTestPage.navigateHereFromLoginPage(driver, login, vehicle);

        assertThat("Check car registration", refuseToTestPage.getRegistrationMark(),
                is(vehicle.carReg));
        assertThat("Check VIN", refuseToTestPage.getVin(), is(vehicle.fullVIN));
        assertThat("Check car make and model", refuseToTestPage.getMakeAndModel(),
                is(vehicle.getCarMakeAndModel()));

        refuseToTestPage.backToSearch();
    }

}
