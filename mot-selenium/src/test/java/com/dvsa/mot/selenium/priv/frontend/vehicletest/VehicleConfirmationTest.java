package com.dvsa.mot.selenium.priv.frontend.vehicletest;


import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration1And2;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults1And2;
import com.dvsa.mot.selenium.datasource.enums.FuelTypes;
import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.datasource.enums.VehicleClasses;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.MotTestApi;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.*;
import org.joda.time.DateTime;
import org.joda.time.Period;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;
import static org.testng.Assert.assertEquals;
import static org.testng.Assert.assertTrue;

public class VehicleConfirmationTest extends BaseTest {

    @Test(groups = {"Regression", "VM-2073"})
    public void testDisplayExpiryMotDateMessageMoreThanOneMonthPreviousPassedTestExpirationDateInTest() {

        Login tester = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);

        createMotTest(tester, Site.POPULAR_GARAGES, vehicle, 78906, MotTestApi.TestOutcome.PASSED,
                new DateTime().minus(Period.months(10)));

        assertThat("Check the content of expiry information alert", StartTestConfirmation1Page
                .navigateHereFromLoginPageAsMotTest(driver, tester, vehicle).getExpiryInfoAlert()
                .contains(Assertion.ASSERTION_PRESERVE_MOT_EXPIRY_DATE_ADVICE.assertion), is(true));
    }

    @Test(groups = {"Regression", "VM-2531", "VM-2384", "VM-5018"},
            description = "Edit the fuel type presented to the confirmation page and ensure the new fuel type is updated successfully")
    public void testEditFuelTypeInVehicleConfirmationTest() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        StartTestConfirmation1Page startTestConfirmation1Page = StartTestConfirmation1Page
                .navigateHereFromLoginPageAsMotTest(driver, login, vehicle);

        assertEquals(startTestConfirmation1Page.getFuel(), vehicle.fuelType.getFuelName());

        MotTestPage motTestPage =
                startTestConfirmation1Page.selectVehicleFuel(FuelTypes.Electric).startTest();

        assertThat("Check the content of vehicle details information",
                motTestPage.getVehicleDetailsInfo().contains(FuelTypes.Electric.getFuelName()),
                is(true));

        motTestPage.cancelMotTest(ReasonToCancel.REASON_VEHICLE_REGISTERED_ERROR).clickLogout();
    }

    @Test(groups = {"VM-5018", "Regression", "W-Sprint6", "VM-769", "VM-2082"})
    public void testConfirmationPageSearchAgain() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);
        Vehicle searchAgainVehicle = createVehicle(Vehicle.VEHICLE_CLASS4_SUBARU_IMPREZA);
        StartTestConfirmation1Page startTestConfirmation1Page = StartTestConfirmation1Page
                .navigateHereFromLoginPageAsMotTest(driver, login, vehicle);

        //Assert search results
        assertThat("Assert Reg",
                startTestConfirmation1Page.getRegistration().contains(vehicle.carReg), is(true));
        assertThat("Assert fuel type",
                startTestConfirmation1Page.getFuel().contains(vehicle.fuelType.toString()),
                is(true));
        assertThat("Assert Make & Model", startTestConfirmation1Page.getCarMakeAndModel()
                .contains(vehicle.getCarMakeAndModel().toString()), is(true));
        assertThat("Assert Transmission type",
                startTestConfirmation1Page.getTransmission().contains(vehicle.transType.toString()),
                is(true));

        //Click Search Again link
        startTestConfirmation1Page.clickSearchAgain().clickSearchAgainLink().submitSearch(searchAgainVehicle);

        assertThat("Validate that the Start MOT test button is displayed on page",
                startTestConfirmation1Page.isStartMotTestButtonDisplayed(), is(true));

        //Assert search results
        assertThat("Assert Reg",
                startTestConfirmation1Page.getRegistration().contains(searchAgainVehicle.carReg),
                is(true));
        assertThat("Assert fuel type", startTestConfirmation1Page.getFuel()
                .contains(searchAgainVehicle.fuelType.toString()), is(true));
        assertThat("Assert Make & Model", startTestConfirmation1Page.getCarMakeAndModel()
                .contains(searchAgainVehicle.getCarMakeAndModel().toString()), is(true));
        assertThat("Assert Transmission type", startTestConfirmation1Page.getTransmission()
                .contains(searchAgainVehicle.transType.toString()), is(true));

        startTestConfirmation1Page.clickSearchAgain().clickCancel().startMotTest().clickLogout();

    }

    @Test(groups = {"VM-5018", "Regression", "W-Sprint6"})
    public void testConfirmationPageCancelAndReturnToHomepage() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);

        StartTestConfirmation1Page.navigateHereFromLoginPageAsMotTest(driver, login, vehicle)
                .clickCancel();
        UserDashboardPage userDashboardPage = new UserDashboardPage(driver);

        assertThat(
                "Validate user is navigated to home page after vehicle confirmation is cancelled",
                userDashboardPage.isStartMotTestDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "VM-767"},
            description = "To view and edit vehicle test class so that the vehicle record match matches the vehicle presented for test")
    public void testEditVehicleClass() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        StartTestConfirmation1Page startTestConfirmation1Page = StartTestConfirmation1Page
                .navigateHereFromLoginPageAsMotTest(driver, login, vehicle);

        assertThat("Assert vehicle class", startTestConfirmation1Page.getVehicleClass(),
                is(vehicle.vehicleClass.getId()));

        MotTestPage motTestPage =
                startTestConfirmation1Page.selectVehicleClass(VehicleClasses.one).startTest();
        motTestPage.addMotTest("30000",
                BrakeTestConfiguration1And2.brakeTestConfigClasses1And2_CASE1(),
                BrakeTestResults1And2.serviceBrakeControlPassA(), null, null, null, null);
        motTestPage.createCertificate();
        TestSummary testSummary = new TestSummary(driver);

        assertThat("Assert vehicle class", testSummary.getTestClass(),
                is(VehicleClasses.one.getId()));

        testSummary.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint().clickBackToHomeLink()
                .clickLogout();
    }

    @Test(groups = {"Regression", "VM-2719", "Sprint 21", "MOT Testing"},
            description = "User selects to test a vehicle class that he is not authorised to test. A warning message is displayed.")
    public void testTesterTryTestUnauthorisedVehicleClass() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        StartTestConfirmation1Page
                .navigateHereFromLoginPageAsMotTest(driver, Login.LOGIN_CATBTESTER, vehicle)
                .selectVehicleClass(VehicleClasses.one).submitConfirmExpectingError();

        assertThat("Assert error message", ValidationSummary.isValidationSummaryDisplayed(driver),
                is(true));
    }

    //TODO return test to Regression suite after stabilising
    @Test(groups = {"Unstable", "VM-2197", "Sprint 21", "MOT Testing"},
            description = "User search a vehicle which is not in the VTR table.All DVLA V5 info is imported in the confirmation page in order to create a new register in VTR table with all DVSA V5 data")
    public void testDVLAdataIsImportedWhenSearchVehicleWithoutVTRRecord() {

        Vehicle vehicle = Vehicle.VEHICLE_CLASS4_EXIST_ONLY_IN_DVSA_VEHICLE_INFO;

        StartTestConfirmation1Page startTestConfirmation1Page = StartTestConfirmation1Page
                .navigateHereFromLoginPageAsMotTest(driver, login, vehicle);

        assertThat("Assert vehicle registration", startTestConfirmation1Page.getRegistration(),
                is(vehicle.carReg));
        assertThat("Assert vehicle VIN", startTestConfirmation1Page.getVIN(), is(vehicle.fullVIN));
        assertThat("Assert vehicle make and model", startTestConfirmation1Page.getCarMakeAndModel(),
                is(vehicle.getCarMakeAndModel()));
        assertThat("Assert vehicle fuel type", startTestConfirmation1Page.getFuel(),
                is(vehicle.fuelType.getFuelName()));
        assertThat("Assert vehicle primary colour", startTestConfirmation1Page.getPrimaryColor(),
                is(vehicle.primaryColour.getColourName()));
        assertThat("Assert vehicle secondary colour",
                startTestConfirmation1Page.getSecondaryColor(),
                is(vehicle.secondaryColour.getColourName()));
    }

    @Test(groups = {"Regression", "Sprint 23", "MOT Testing", "VM-2728", "VM-2726", "VM-2725"},
            description = "A vehicle can only be registered for test at one VTS, when a vehicle is registered for test at a site it must be blocked form being registered for test at another site.")
    public void testOnlyOneActiveTestOnAVehicleAtATime() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        MotTestPage.navigateHereFromLoginPage(driver, login, vehicle).clickLogout();
        StartTestConfirmation1Page confirmationPage = StartTestConfirmation1Page
                .navigateHereFromLoginPageAsMotTest(driver, Login.LOGIN_TESTER4, vehicle);

        assertThat("Assert in progress test exist alert message display",
                confirmationPage.getInProgressTestExistsAlert(),
                is(Assertion.ASSERTION_VEHICLE_CURRENTLY_UNDER_TEST.assertion));
    }

    @Test(groups = {"VM-5189"}) public void testerCanAmendTechnicalRecordForClass4Vehicle() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        StartTestConfirmation1Page startTestConfirmation1Page = StartTestConfirmation1Page
                .navigateHereFromLoginPageAsMotTest(driver, login, vehicle);
        startTestConfirmation1Page.selectVehicleFuel(FuelTypes.Diesel)
                .submitConfirmExpectingVehicleDetailsChangedPage()
                .confirmVehicleChanges(Text.TEXT_PASSCODE);

        MotTestStartedPage motTestStartedPage =
                new MotTestStartedPage(driver, PageTitles.MOT_TEST_STARTED.getPageTitle());
        assertTrue(motTestStartedPage.isSignOutButtonDisplayed(),
                "Validate that the sign out button is displayed after vehicle details gets changed");
        startTestConfirmation1Page.clickLogout();
    }
}
