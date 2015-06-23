package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResultsPageField;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestSummary4;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.framework.api.MotTestApi.TestOutcome;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages.BrakeTestSummaryPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.TestSummary;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.VehicleConfirmationRetestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.VehicleSearchRetestPage;
import org.apache.commons.lang3.RandomStringUtils;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import java.util.Arrays;
import java.util.Map;
import java.util.UUID;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class VehicleSearchRetestTest extends BaseTest {

    AeService aeService = new AeService();


    @Test(groups = {"Regression", "VM-769", "VM-1862", "short-vehicle"})
    public void testSearchRetestByPreviousNumber() {

        Login login = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);
        Site site = Site.POPULAR_GARAGES;
        String motTestNumber = createMotTest(login, site, vehicle, 67891, TestOutcome.FAILED);

        VehicleSearchRetestPage.navigateHereFromLoginPage(driver, login)
                .submitSearchWithPreviousTestNumber(motTestNumber);

        assertThat("Assert MOT Test Number is not null", motTestNumber.isEmpty(), is(false));

        VehicleConfirmationRetestPage vehicleConfirmationRetestPage =
                new VehicleConfirmationRetestPage(driver);

        assertThat("Check the vehicle VIN", vehicleConfirmationRetestPage.getVIN(),
                is(vehicle.fullVIN));
    }

    @Test(groups = {"Regression", "VM-769", "short-vehicle"})
    public void testEnterInvalidRetestNumber() {

        Login login = createTester();
        String invalidRestNumber = "....";

        VehicleSearchRetestPage.navigateHereFromLoginPage(driver, login)
                .submitSearchWithPreviousTestNumberExpectingError(invalidRestNumber);

        assertThat("Check validation summary message",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
    }

    @Test(groups = {"Regression", "VM-769", "VM-1862"})
    public void testSearchRetestByPreviousNumberAndVinExpectingError() {

        Login login = createTester();

        VehicleSearchRetestPage.navigateHereFromLoginPage(driver, login)
                .submitSearchWithPreviousTestNumberExpectingError("X");

        assertThat("Validate that error message is displayed",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
    }

    @Test(groups = {"Regression", "VM-1862", "short-vehicle"})
    public void testSearchRetestByRegistrationNumberAndVin() {

        AeDetails aeDetails = aeService.createAe(RandomStringUtils.randomAlphabetic(5));
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        RandomStringUtils.randomAlphabetic(5));
        Login login = createTester(Arrays.asList(site.getId()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002);

        createMotTest(login, site, vehicle, 50000, TestOutcome.FAILED);

        VehicleSearchRetestPage.navigateHereFromLoginPage(driver, login)
                .submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg);
        VehicleConfirmationRetestPage vehicleConfirmationRetestPage =
                new VehicleConfirmationRetestPage(driver);

        assertThat("Check the vehicle VIN", vehicleConfirmationRetestPage.getVIN(),
                is(vehicle.fullVIN));
    }


    @Test(groups = {"Regression", "VM-1862", "short-vehicle"})
    public void testStartRetestAndClickConfirmVehicle() {

        AeDetails aeDetails = aeService.createAe(RandomStringUtils.randomAlphabetic(5));
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        RandomStringUtils.randomAlphabetic(5));
        Login login = createTester(Arrays.asList(site.getId()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005);

        VehicleConfirmationRetestPage vehicleConfirmationRetestPage =
                VehicleConfirmationRetestPage.navigateHereFromLoginPage(driver, login, vehicle);

        assertThat("Reject message is incorrect", vehicleConfirmationRetestPage.getRejectMessage(),
                is(Assertion.ASSERTION_ORIGINAL_TEST_NOT_PERFORMED.assertion));
    }

    @Test(groups = {"Regression", "VM-1862"}) public void testStartRetestAndClickSearchAgain() {

        AeDetails aeDetails = aeService.createAe(RandomStringUtils.randomAlphabetic(5));
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        RandomStringUtils.randomAlphabetic(5));
        Login login = createTester(Arrays.asList(site.getId()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924);

        createMotTest(login, site, vehicle, 50000, TestOutcome.FAILED);

        VehicleConfirmationRetestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .searchAgain();
        VehicleSearchRetestPage vehicleSearchRetestPage = new VehicleSearchRetestPage(driver);

        assertThat("Validate user is navigated back to Vehicle Search page",
                vehicleSearchRetestPage.isVehicleSearchFormDisplayed(), is(true));
    }

    @DataProvider(name = "DP-MultipleRFRs")
    public Object[][] testClass4MOTFailWithMultipleRFRsData() {
        return new Object[][] {
                {createTester(), BrakeTestResults4.allFail(), BrakeTestSummary4.allFailResults,
                        BrakeTestResults4.allPass(), BrakeTestSummary4.allPassResults,
                        createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004), "Fail", RandomDataGenerator
                        .generateRandomNumber(5, UUID.randomUUID().hashCode())},};
    }

    @Test(description = "Test fail MOT with RFR and retest with Pass result.",
            groups = {"VM-1666", "VM-315", "VM-1661", "Regression"}, dataProvider = "DP-MultipleRFRs")
    public void testClass4FailWithRFRAndRetestWithPassResult(Login login,
            Map<BrakeTestResultsPageField, Object> inputsBrakeFailure,
            Map<String, String> expResultBrakeFailure,
            Map<BrakeTestResultsPageField, Object> inputsBrakePass,
            Map<String, String> expResultBrakePass, Vehicle vehicle, String expPassFailStatus,
            String odometerReading) {

        BrakeTestSummaryPage brakeTestSummary = new LoginPage(driver)
                .loginSearchVINandRegGoToMotTestResults(login.username, login.password,
                        vehicle.fullVIN, vehicle.carReg).addRFR()
                .addFailure(FailureRejection.BALLJOINT_EXCESSIVELY_DETERIORATED).clickDone()
                .enterOdometerValuesAndSubmit(odometerReading).addBrakeTest()
                .enterBrakeConfigurationPageFields(
                        BrakeTestConfiguration4.brakeTestConfigClass4_CASE1()).submit()
                .enterBrakeResultsPageFields(inputsBrakeFailure).submit();

        assertThat("Compare actual brake results with expected values",
                brakeTestSummary.getResultsMap(), is(expResultBrakeFailure));

        //Click Done & Create Certificate
        TestSummary testSummaryPage = brakeTestSummary.clickDoneButton().createCertificate();

        assertThat("Assert RFR Failures on Test Summary page", testSummaryPage.getRfrDetails()
                        .contains(
                                FailureRejection.BALLJOINT_EXCESSIVELY_DETERIORATED.reason.reasonDescription),
                is(true));
        assertThat("Assert MOT Test result (Pass or Fail)", testSummaryPage.getTestStatus(),
                is(expPassFailStatus));
        assertThat("Assert Reg", testSummaryPage.getRegNumber(), is(vehicle.carReg));
        assertThat("Assert VIN", testSummaryPage.getVin(), is(vehicle.fullVIN));
        assertThat("Assert Colour",
                testSummaryPage.getColour().startsWith(vehicle.primaryColour.getColourName()),
                is(true));
        assertThat("Assert Make", testSummaryPage.getMake(), is(vehicle.make.getVehicleMake()));
        assertThat("Assert Model", testSummaryPage.getModel(), is(vehicle.model.getModelName()));
        assertThat("Assert odometer reading", String.valueOf(testSummaryPage.getOdometerReading()),
                is(odometerReading));
        //end of MOT FAIL TEST
        //Get MOT Test Number
        String motTestNumber = testSummaryPage.getMotTestNumber();

        assertThat("Assert MOT Test Number is not null", motTestNumber.isEmpty(), is(false));

        //Perform MOT Retest
        testSummaryPage.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint().clickDoneButton()
                .startMotRetest().submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg)
                .startTest().enterOdometerValuesAndSubmit(odometerReading).addBrakeTest()
                .enterBrakeConfigurationPageFields(
                        BrakeTestConfiguration4.brakeTestConfigClass4_CASE1()).submit()
                .enterBrakeResultsPageFields(inputsBrakePass).submit();

        assertThat("Compare actual brake results with expected values",
                brakeTestSummary.getResultsMap(), is(expResultBrakePass));

        TestSummary summaryScreenRetest =
                brakeTestSummary.clickDoneExpectingMotRetestPage().createCertificate();

        assertThat("Assert MOT Test result (Pass or Fail)", summaryScreenRetest.getTestStatus(),
                is("Fail"));
        assertThat("Assert Reg", summaryScreenRetest.getRegNumber(), is(vehicle.carReg));
        assertThat("Assert VIN", summaryScreenRetest.getVin(), is(vehicle.fullVIN));
        assertThat("Assert Colour",
                summaryScreenRetest.getColour().startsWith(vehicle.primaryColour.toString()),
                is(true));
        assertThat("Assert Make", summaryScreenRetest.getMake(), is(vehicle.make.getVehicleMake()));
        assertThat("Assert Model", summaryScreenRetest.getModel(),
                is(vehicle.model.getModelName()));
        assertThat("Assert odometer reading",
                String.valueOf(summaryScreenRetest.getOdometerReading()), is(odometerReading));

        //Get MOT Test Number
        String motTestNumberRetest = summaryScreenRetest.getMotTestNumber();

        assertThat("Assert MOT Test Number is not null", motTestNumberRetest.isEmpty(), is(false));

        //Click finish and print
        summaryScreenRetest.enterNewPasscode(Text.TEXT_PASSCODE)
                .clickFinishPrint().clickDoneButton()
                //Log the user out of the system
                .clickLogout();
    }


}
