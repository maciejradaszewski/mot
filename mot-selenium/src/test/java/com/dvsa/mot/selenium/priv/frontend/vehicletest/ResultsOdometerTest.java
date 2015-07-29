package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.ReasonToCancel;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages.BrakeTestSummaryPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.TestSummary;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;
import static org.hamcrest.Matchers.startsWith;

public class ResultsOdometerTest extends BaseTest {

    @Test(groups = "Regression", description = "Ensure that default units set for odometer reading are miles")
    public void testOdometerDefaultUnit() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        MotTestPage motTestPage =
                MotTestPage.navigateHereFromLoginPage(driver, login, vehicle).clickUpdateOdometer();

        assertThat("Default units", motTestPage.getSelectedOdometerUnit(), is("Miles"));

        motTestPage.cancelMotTest(ReasonToCancel.REASON_ACCIDENT_OR_ILLNESS);
    }

    @Test(groups = {"Regression",
            "short",}, description = "Ensure that the proper value is displayed with miles and the 'odometer reading updated' info is displayed")
    public void testSubmitProperValueMilesCheckDisplayed() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .enterOdometerValuesAndSubmit(Text.TEXT_VALID_ODOMETER_MILES);

        assertThat("Displayed value or units for Odometer Reading",
                motTestPage.getDisplayedOdometerReading(),
                startsWith(Text.TEXT_VALID_ODOMETER_MILES + " miles"));

        motTestPage.cancelMotTest(ReasonToCancel.REASON_VEHICLE_REGISTERED_ERROR);
    }

    @Test(groups = "Regression", description = "Ensure that when proper values are entered, they are displayed as 'km' rather than 'miles'")
    public void testSubmitProperValueKmCheckDisplayed() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle);
        motTestPage.enterOdometerValuesAndUnit(Text.TEXT_VALID_ODOMETER_KM, "km");

        assertThat("Displayed value or units for Odometer Reading",
                motTestPage.getDisplayedOdometerReading(),
                startsWith(Text.TEXT_VALID_ODOMETER_KM + " km"));

        motTestPage.cancelMotTest(ReasonToCancel.REASON_ABORTED_BY_VE);
    }

    @Test(groups = "Regression", description = "Ensure that an error message is returned when an empty odometer reading is submitted")
    public void testEmptyOdometerAtFirstTime() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .enterOdometerValuesAndSubmit("");

        assertThat("Odometer Reading", motTestPage.getDisplayedOdometerReading(),
                is("Not recorded"));
        assertThat("Validation message", ValidationSummary.isValidationSummaryDisplayed(driver),
                is(true));

        motTestPage.cancelMotTest(ReasonToCancel.REASON_INCORRECT_LOCATION);
    }

    @Test(groups = "Regression", description = "Ensure that no more than 7 digits can be entered into the odometer field")
    public void testSubmitMoreThanLimitChars() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_MONDEO_2002);

        TestSummary.navigateHereFromLoginPage(driver, login, vehicle, "12349",
                BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(), BrakeTestResults4.allPass(),
                null, null, null, null).clickFinishPrint(Text.TEXT_PASSCODE).clickLogout();
        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .enterOdometerValuesAndSubmit("1234567");

        assertThat("Displayed value or units for Odometer Reading",
                motTestPage.getDisplayedOdometerReading(), startsWith("123456 miles"));
        assertThat("Check odometer reading notice", motTestPage.getOdometerReadingNotice(),
                is(Assertion.ASSERTION_VALUE_SIGNIFICANTLY_HIGHER.assertion));

        motTestPage.cancelMotTest(ReasonToCancel.REASON_ACCIDENT_OR_ILLNESS);
    }

    @Test(groups = "Regression", description = "Ensure that no more than 999999 miles can be entered into the odometer field")
    public void testSubmitMaxValueMiles() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        TestSummary.navigateHereFromLoginPage(driver, login, vehicle, "12350",
                BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(), BrakeTestResults4.allPass(),
                null, null, null, null).clickFinishPrint(Text.TEXT_PASSCODE).clickLogout();
        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .enterOdometerValuesAndSubmit(999999);

        assertThat("Displayed value or units for Odometer Reading",
                motTestPage.getDisplayedOdometerReading(), startsWith("999999 miles"));
        assertThat("Check odometer reading notice", motTestPage.getOdometerReadingNotice(),
                is(Assertion.ASSERTION_VALUE_SIGNIFICANTLY_HIGHER.assertion));

        motTestPage.cancelMotTest(ReasonToCancel.REASON_INCORRECT_LOCATION);
    }

    @Test(enabled = true, description = "MOT test cannot be completed with blank Odometer value",
            groups = {"Regression"}) public void testMOTCannotCompleteTestWithBlankOdometer() {

        MotTestPage motTest = BrakeTestSummaryPage.navigateHereFromLoginPage(driver, createTester(),
                createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                BrakeTestResults4.endToEndTest50ServiceBrake()).clickDoneButton();

        assertThat("Assert Review button is disabled", motTest.isReviewButtonEnabled(), is(false));

        motTest.cancelMotTest(ReasonToCancel.REASON_TEST_EQUIPMENT_ISSUE).clickLogout();

        //Assert user is logged out of the application
        assertThat("Assert user is logged out of the application",
                new LoginPage(driver).isUserLoggedIn(), is(false));
    }
}
