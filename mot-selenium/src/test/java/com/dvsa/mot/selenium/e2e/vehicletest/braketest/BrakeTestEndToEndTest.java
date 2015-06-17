package com.dvsa.mot.selenium.e2e.vehicletest.braketest;

import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResultsPageField;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestSummary4;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages.BrakeTestSummaryPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.TestSummary;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import java.util.Map;

import static org.testng.Assert.*;

//In order to conduct a test as a Tester I want \ need capture odometer and brake test values to enable a test pass

//Brake imbalance calculation (per axle): (Higher brake effort - Lower brake effort) / Higher brake effort * 100
//>= 30% PASS , <=29.9% FAIL


public class BrakeTestEndToEndTest extends BaseTest {

    /**
     * Provide the parameter data for <b>testBrakeTestResults</b>.
     * It passes pair of arrays of Strings: first with input values to insert in
     * Brake Test Result page, second expected values on Brake Test Summary page
     */
    @DataProvider(name = "DP-BrakeTestDataE2E", parallel = false)
    public Object[][] parameterBrakeTestDataProvider() {
        return new Object[][] {
                {BrakeTestResults4.imbalanceAxl2Only(),
                        BrakeTestSummary4.imbalanceFailAxl2OnlyResults,
                        createVehicle(Vehicle.VEHICLE_CLASS4_MONDEO_2002), "Fail", "10000"},
                {BrakeTestResults4.imbalanceEdgeAxl1Pass(),
                        BrakeTestSummary4.imbalanceEdgeAxl1PassResults,
                        createVehicle(Vehicle.VEHICLE_CLASS4_SUBARU_IMPREZA), "Pass", "10000"},
                {BrakeTestResults4.imbalanceEdgeAxl1Fail(),
                        BrakeTestSummary4.imbalanceEdgeAxl1FailResults,
                        createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004), "Fail", "10000"},
                {BrakeTestResults4.imbalanceEdgeAxl2Pass(),
                        BrakeTestSummary4.imbalanceEdgeAxl2PassResults,
                        createVehicle(Vehicle.VEHICLE_CLASS4_BOXSTER_2001), "Pass", "10000"},
                {BrakeTestResults4.imbalanceEdgeAxl2Fail(),
                        BrakeTestSummary4.imbalanceEdgeAxl2FailResults,
                        createVehicle(Vehicle.VEHICLE_CLASS4_MONDEO_2002), "Fail", "10000"},
        };
    }

    /**
     * Create new MOT record for pre 2010 vehicle with various brake test results
     */
    @Test(description = "Brake pass scenarios for pre 2010 Class 4 vehicle.", groups = {"VM-60",
            "short-brakes", "short-vehicle", "slice_A"},
            dataProvider = "DP-BrakeTestDataE2E") public void brakeTestClass4(
            Map<BrakeTestResultsPageField, Object> inputs, Map<String, String> expResult,
            Vehicle vehicle, String expPassFailStatus, String odometerReading) {

        BrakeTestSummaryPage brakeTestSummary =
                MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                        .enterOdometerValuesAndSubmit(odometerReading).addBrakeTest()
                        .enterBrakeConfigurationPageFields(
                                BrakeTestConfiguration4.brakeTestConfigClass4_CASE1()).submit()
                        .enterBrakeResultsPageFields(inputs).submit();

        //Compare actual brake results with expected values
        assertEquals(brakeTestSummary.getResultsMap(), expResult);

        //Click Done & Create Certificate
        TestSummary summaryScreen = brakeTestSummary.clickDoneButton().createCertificate();

        assertEquals(summaryScreen.getTestStatus(), expPassFailStatus,
                "Assert MOT Test result (Pass or Fail)");
        assertEquals(summaryScreen.getRegNumber(), vehicle.carReg, "Assert Reg");
        assertEquals(summaryScreen.getVin(), vehicle.fullVIN, "Assert VIN");
        assertTrue(summaryScreen.getColour().startsWith(vehicle.primaryColour.getColourName()),
                "Assert Colour");
        assertEquals(summaryScreen.getMake(), vehicle.make.getVehicleMake(), "Assert Make");
        assertEquals(summaryScreen.getModel(), vehicle.model.getModelName(), "Assert Model");

        //Assert MOT Test Number is not null
        assertNotNull(summaryScreen.getMotTestNumber());

        //Click finish and print
        summaryScreen.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint().clickDoneButton();
    }
}
