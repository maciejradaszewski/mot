package com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest;

import com.dvsa.mot.selenium.datasource.ReasonToCancel;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.braketest.*;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages.BrakeTestResultsPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages.BrakeTestSummaryPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import org.testng.Assert;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import java.util.Map;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class BrakeTestResultsTest extends BaseTest {
    private static ReasonToCancel reasonToCancel = ReasonToCancel.REASON_VEHICLE_REGISTERED_ERROR;

    @DataProvider(name = "brakeTestResultsProvider") private Object[][] brakeTestResultsProvider() {
        return new Object[][] {

                {true, createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002),
                        BrakeTestConfiguration1And2.brakeTestConfigClasses1And2_CASE1(),
                        BrakeTestResults1And2.serviceBrakeControlFailA(),
                        BrakeTestSummary1And2.serviceBrakeControlFailAResults},

                {true, createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011), BrakeTestConfiguration3
                        .brakeTestConfigClass3_DecelerometerAndDecelerometer(),
                        BrakeTestResults3.passDecelerometer_Decelerometer(),
                        BrakeTestSummary3.passDecelerometer_Decelerometer},

                {true, createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010), BrakeTestConfiguration4
                        .brakeTestConfigClass4_DecelerometerAndDecelerometer(),
                        BrakeTestResults4.failDecelerometer_Decelerometer(),
                        BrakeTestSummary4.failDecelerometer_Decelerometer},
                {true, createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.allFail(), BrakeTestSummary4.allFailResults},

                {true, createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        BrakeTestConfiguration4.brakeTestConfigClass4_Roller3Axles(),
                        BrakeTestResults4.allPass_3Axles(),
                        BrakeTestSummary4.allPassResults_3Axles},

                {true, createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924),
                        BrakeTestConfiguration5.brakeTestConfigClass5_DecelerometerAndGradient(),
                        BrakeTestResults5.failDecelerometer_Gradient(),
                        BrakeTestSummary5.failServiceBrakeDecelerometer_Gradient},

                {true, createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_CASE4(),
                        BrakeTestResults7.rearAxleImbalance30Pass_Class7(),
                        BrakeTestSummary7.rearAxleImbalance30PassResults_Class7},
                {true, createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_PlateAndPlate_2Axles(),
                        BrakeTestResults7.class7ServiceAndParkingBrakePassA(),
                        BrakeTestSummary7.class7PlateServiceAndParkingBrakePassAResults},
                {true, createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_PlateAndPlate_2Axles(),
                        BrakeTestResults7.class7ServiceAndParkingBrakeFailA(),
                        BrakeTestSummary7.class7PlateServiceAndParkingBrakeFailAResults}};
    }

    @Test(dataProvider = "brakeTestResultsProvider",
            groups = {"Regression", "VM-990", "VM-1030", "VM-1339", "VM-1337", "VM-1338", "VM-1029",
                    "VM-1028"}) public void testBrakeTestResults(boolean runTest, Vehicle vehicle,
            Map<BrakeTestConfigurationPageField, Object> configurationDetails,
            Map<BrakeTestResultsPageField, Object> resultsDetails, Map<String, String> expResult) {
        if (runTest) {
            BrakeTestSummaryPage brakeTestSummaryPage = BrakeTestSummaryPage
                    .navigateHereFromLoginPage(driver, login, vehicle, configurationDetails,
                            resultsDetails);
            Assert.assertEquals(expResult, brakeTestSummaryPage.getResultsMap());
            brakeTestSummaryPage.clickDoneButton().cancelMotTest(reasonToCancel);
        }
    }

    @DataProvider(name = "brakeTestResultsShortGroupProvider")
    private Object[][] brakeTestResultsShortGroupProvider() {
        return new Object[][] {{createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002),
                BrakeTestConfiguration1And2.brakeTestConfigClasses1And2_WITH_SIDECAR(),
                BrakeTestResults1And2.passControlsOver30WithSidecar(),
                BrakeTestSummary1And2.passControlsOver30WithSidecarResults},
                {createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002),
                        BrakeTestConfiguration1And2.brakeTestConfigClasses1And2_WITH_SIDECAR(),
                        BrakeTestResults1And2.failControlsUnder30WithSidecar(),
                        BrakeTestSummary1And2.failControlsUnder30WithSidecarResults},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_CASE4(),
                        BrakeTestResults7.rearAxleImbalance30Fail_Class7(),
                        BrakeTestSummary7.rearAxleImbalance30FailResults_Class7},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        BrakeTestConfiguration4.brakeTestConfigClass4_PlateAndPlate(),
                        BrakeTestResults4.allPassPlatePlate(),
                        BrakeTestSummary4.allPassResultsPlatePlate},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        BrakeTestConfiguration4.brakeTestConfigClass4_PlateAndPlate(),
                        BrakeTestResults4.allFailPlatePlate(),
                        BrakeTestSummary4.allFailResultsPlatePlate},
                {createVehicle(Vehicle.VEHICLE_CLASS3_HARLEY_DAVIDSON_1961),
                        BrakeTestConfiguration3.brakeTestConfigClass3_CASE2(),
                        BrakeTestResults3.passSingleWheelRear(),
                        BrakeTestSummary3.passSingleWheelRearResults},};
    }

    @Test(dataProvider = "brakeTestResultsShortGroupProvider",
            groups = {"Regression", "short-brakes", "short", "VM-860"})
    public void testBrakeTestResultsShortGroup(Vehicle vehicle,
            Map<BrakeTestConfigurationPageField, Object> configurationDetails,
            Map<BrakeTestResultsPageField, Object> resultsDetails, Map<String, String> expResult) {
        BrakeTestSummaryPage brakeTestSummaryPage = BrakeTestSummaryPage
                .navigateHereFromLoginPage(driver, login, vehicle, configurationDetails,
                        resultsDetails);
        Assert.assertEquals(expResult, brakeTestSummaryPage.getResultsMap());
        brakeTestSummaryPage.clickDoneButton().cancelMotTest(reasonToCancel);
    }

    @DataProvider(name = "brakeTestFinishAndPrintShortProvider")
    private Object[][] brakeTestFinishAndPrintShortProvider() {
        return new Object[][] {{createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924),
                BrakeTestConfiguration5.brakeTestConfigClass5_Roller(),
                BrakeTestResults5.passClass5(), BrakeTestSummary5.passResultsClass5}};
    }

    @Test(dataProvider = "brakeTestFinishAndPrintShortProvider",
            groups = {"Regression", "VM-1030", "VM-1029", "short"})
    public void testBrakeTestFinishAndPrintShort(Vehicle vehicle,
            Map<BrakeTestConfigurationPageField, Object> configurationDetails,
            Map<BrakeTestResultsPageField, Object> resultsDetails, Map<String, String> expResult) {
        BrakeTestSummaryPage brakeTestSummaryPage = BrakeTestSummaryPage
                .navigateHereFromLoginPage(driver, login, vehicle, configurationDetails,
                        resultsDetails);
        Assert.assertEquals(expResult, brakeTestSummaryPage.getResultsMap());
        brakeTestSummaryPage.clickDoneButton().enterOdometerValuesAndSubmit("20900")
                .createCertificate().clickFinishPrint(Text.TEXT_PASSCODE).clickBackToHomeLink();
    }

    @DataProvider(name = "editBrakeTestResultsProvider")
    private Object[][] editBrakeTestResultsProvider() {
        return new Object[][] {{Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005,
                BrakeTestConfiguration7.brakeTestConfigClass7_CASE4(),
                BrakeTestResults7.class7ServiceAndParkingBrakePassC(),
                BrakeTestConfiguration7.brakeTestConfigClass7_CASE2(),
                BrakeTestResults7.class7ServiceAndParkingBrakePassC(),
                BrakeTestSummary7.class7GradientServiceAndParkingBrakePassCResults},
                {Vehicle.VEHICLE_CLASS5_STREETKA_1924,
                        BrakeTestConfiguration5.brakeTestConfigClass5_Roller_3Axles(),
                        BrakeTestResults5.serviceAndParkingBrakePassA_3Axles(),
                        BrakeTestConfiguration5.editBrakeTestConfigClass5_Roller_3Axles(),
                        BrakeTestResults5.serviceAndParkingBrakePassA_3Axles(),
                        BrakeTestSummary5.serviceAndParkingBrakePassAResults_3Axles},};
    }

    @Test(dataProvider = "editBrakeTestResultsProvider", groups = {"Regression", "VM-1030"})
    public void testEditBrakeResultsSuccessfully(Vehicle vehicle,
            Map<BrakeTestConfigurationPageField, Object> configurationDetails,
            Map<BrakeTestResultsPageField, Object> resultDetails,
            Map<BrakeTestConfigurationPageField, Object> configurationEditDetails,
            Map<BrakeTestResultsPageField, Object> resultEditDetails,
            Map<String, String> expResult) {

        BrakeTestSummaryPage brakeTestSummaryPage = BrakeTestSummaryPage
                .navigateHereFromLoginPage(driver, login, vehicle, configurationDetails,
                        resultDetails);
        Assert.assertEquals(brakeTestSummaryPage.getResultsMap(), expResult);
        brakeTestSummaryPage.clickEditButton()
                .enterBrakeConfigurationPageFields(configurationEditDetails).submit()
                .enterBrakeResultsPageFields(resultEditDetails).submit().clickDoneButton()
                .cancelMotTest(reasonToCancel);
    }

    @DataProvider(name = "DP-testWhenFailBrakeImbalanceAddSeparateRFRAddedForEachAxle")
    private Object[][] whenFailBrakeImbalanceAddSeparateRFRAddedForEachAxleProvider() {
        return new Object[][] {{Vehicle.VEHICLE_CLASS5_STREETKA_1924,
                BrakeTestConfiguration5.brakeTestConfigClass5_Roller(),
                BrakeTestResults5.rearAxleImbalance31Fail(), 2},
                {Vehicle.VEHICLE_CLASS5_STREETKA_1924,
                        BrakeTestConfiguration5.brakeTestConfigClass5_Roller(),
                        BrakeTestResults5.rearAxleImbalance31FailAxle1(), 1},
                {Vehicle.VEHICLE_CLASS4_CLIO_2004,
                        BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.imbalanceInAxle1(), 1}, {Vehicle.VEHICLE_CLASS4_CLIO_2004,
                BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                BrakeTestResults4.imbalanceOnly(), 2}};
    }

    @Test(groups = {"Regression", "VM-1842", "short-brakes"},
            dataProvider = "DP-testWhenFailBrakeImbalanceAddSeparateRFRAddedForEachAxle")
    public void testWhenFailBrakeImbalanceAddSeparateRFRAddedForEachAxle(Vehicle vehicle,
            Map<BrakeTestConfigurationPageField, Object> configurationDetails,
            Map<BrakeTestResultsPageField, Object> resultsDetails, int numberExpectedFailures) {

        MotTestPage motTestPage = BrakeTestSummaryPage
                .navigateHereFromLoginPage(driver, login, vehicle, configurationDetails,
                        resultsDetails).clickDoneButton();
        Assert.assertEquals(motTestPage.getNumberOfFailures(), numberExpectedFailures);
        motTestPage.cancelMotTest(reasonToCancel);
    }

    @DataProvider(name = "DP-testVehicleUsedAfter1stSeptember2010NotAllowSingleLineBraking")
    private Object[][] vehicleUsedAfter1stSeptember2010NotAllowSingleLineBrakingProvider() {
        return new Object[][] {{BrakeTestConfiguration4.brakeTestConfigClass4_CASE5_2_Axles(),
                BrakeTestResults4.brakeTestEntry_CASE1()},
                {BrakeTestConfiguration4.brakeTestConfigClass4_CASE5_2_Axles(),
                        BrakeTestResults4.brakeTestEntry_CASE2()}};
    }

    @Test(dataProvider = "DP-testVehicleUsedAfter1stSeptember2010NotAllowSingleLineBraking",
            groups = {"Regression", "VM-1667"})
    public void testVehicleUsedAfter1stSeptember2010NotAllowSingleLineBraking(
            Map<BrakeTestConfigurationPageField, Object> brakeTestConfig,
            Map<BrakeTestResultsPageField, Object> brakeTestEntry) {
        Vehicle vehicle = Vehicle.VEHICLE_CLASS4_FIRST_USE_AFTER_SEPTEMBER_2010;
        BrakeTestResultsPage brakeTestResultsPage = BrakeTestResultsPage
                .navigateHereFromLoginPageAsMotTest(driver, login, vehicle, brakeTestConfig)
                .enterBrakeResultsPageFields(brakeTestEntry).submitExpectingError();

        assertThat(ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
        brakeTestResultsPage.cancel().cancelMotTest(reasonToCancel);
    }
}
