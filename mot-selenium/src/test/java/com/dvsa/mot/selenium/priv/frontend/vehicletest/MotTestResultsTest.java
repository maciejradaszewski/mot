package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.TestSummary;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class MotTestResultsTest extends BaseTest {

    private static ReasonToCancel reasonToCancel = ReasonToCancel.REASON_VEHICLE_REGISTERED_ERROR;

    @Test(groups = {"Regression", "short-vehicle"}) public void testDisplayedVehicleInfo() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        MotTestPage motTestPage =
                MotTestPage.navigateHereFromLoginPage(driver, login, vehicle).clickMoreDetails();

        String make = vehicle.make.getVehicleMake();
        String model = vehicle.model.getModelName();
        String year = vehicle.yearOfManufacture;

        assertThat("Check car make", motTestPage.getCarName().contains(make), is(true));
        assertThat("Check car model", motTestPage.getCarName().contains(model), is(true));
        assertThat("Check year car was made", motTestPage.getCarYear().contains(year), is(true));

        motTestPage.cancelMotTest(reasonToCancel);
    }

    @Test(groups = {"Regression", "VM-2830", "Sprint 21", "MOT Testing"},
            description = "If a tester or a Vehicle examiner selects brake test not tested then the system should not make the user enter brake test results.")
    public void testAddBrakeTestIsNotMandatoryWhenSelectBrakeTestNotTested() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .enterOdometerValuesAndSubmit(12350);

        assertThat("Check if add brake test button is enabled",
                motTestPage.isAddBrakeTestButtonEnabled(), is(true));

        motTestPage.addFailure(FailureRejection.BRAKE_PERFORMANCE_NOT_TESTED);

        assertThat("Check if add brake test button is enabled",
                motTestPage.isAddBrakeTestButtonEnabled(), is(false));
        assertThat("Check if review test button is enabled", motTestPage.isReviewButtonEnabled(),
                is(true));

        motTestPage.cancelMotTest(reasonToCancel);
    }

    @Test(groups = {"Regression", "VM-2830", "Sprint 21", "MOT Testing"},
            description = "To enter brake test figures the user must first remove the RFR for brake test not tested before they can edit or add any brake test results.")
    public void testRFRMustFirstRemovedToAddBrakeTestResults() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .enterOdometerValuesAndSubmit(12350)
                .addFailure(FailureRejection.BRAKE_PERFORMANCE_NOT_TESTED);

        assertThat("Check brake test result notice", motTestPage.getBrakeTestResultsNotice(),
                is(Assertion.ASSERTION_BRAKES_NOT_TESTED.assertion));
        assertThat("Check if add brake test button is enabled",
                motTestPage.isAddBrakeTestButtonEnabled(), is(false));
        assertThat("Check if review test button is enabled", motTestPage.isReviewButtonEnabled(),
                is(true));

        motTestPage.expandAndShowFailures().removeRfR();

        assertThat("Check if add brake test button is enabled",
                motTestPage.isAddBrakeTestButtonEnabled(), is(true));

        motTestPage.cancelMotTest(reasonToCancel);
    }

    @Test(groups = {"Regression", "VM-2586", "Sprint 22", "MOT Testing"},
            description = "When a tester provide 3 invalid passcodes on Mot Test Summary page, he is locked and a link to report a faulty card is displayed")
    public void testTesterIsLockedWhenInsertThreeInvalidPassCode() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        TestSummary testSummary = TestSummary
                .navigateHereFromLoginPage(driver, login, vehicle, "20300",
                        BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.allPass(), null, null, null, null)
                .enterNewPasscode(Text.TEXT_PASSCODE_INVALID).clickFinishPrintExpectingError();

        assertThat("Assert that the invalid pin message is correct",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

        testSummary.enterNewPasscode(Text.TEXT_PASSCODE_INVALID).clickFinishPrintExpectingError();

        assertThat("Assert that the invalid pin message is correct",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

        testSummary.enterNewPasscode(Text.TEXT_PASSCODE_INVALID).clickFinishPrintExpectingError();

        assertThat("3rd invalid pin message is correct",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
    }
}
