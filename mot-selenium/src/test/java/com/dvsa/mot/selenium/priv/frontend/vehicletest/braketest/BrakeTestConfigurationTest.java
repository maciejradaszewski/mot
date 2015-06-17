package com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest;

import com.dvsa.mot.selenium.datasource.ReasonToCancel;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.braketest.*;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages.BrakeTestConfigurationPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.TestSummary;
import org.testng.Assert;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import java.util.Map;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class BrakeTestConfigurationTest extends BaseTest {
    private static ReasonToCancel reasonToCancel = ReasonToCancel.REASON_VEHICLE_REGISTERED_ERROR;

    @DataProvider(name = "cancelConfigurationDetailsProvider")
    public Object[][] cancelConfigurationDetailsProvider() {
        return new Object[][] {{createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002),
                BrakeTestConfiguration1And2.brakeTestConfigClasses1And2_WITH_SIDECAR()},
                {createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011),
                        BrakeTestConfiguration3.brakeTestConfigClass3_CASE1()},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004), BrakeTestConfiguration4
                        .brakeTestConfigClass4_DecelerometerAndDecelerometer()},
                {createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924),
                        BrakeTestConfiguration5.brakeTestConfigClass5_Roller()},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_Roller()}};
    }

    @Test(groups = {"slice_A", "VM-1029"}, dataProvider = "cancelConfigurationDetailsProvider")
    public void testCancelConfigurationDetails(Vehicle vehicle,
            Map<BrakeTestConfigurationPageField, Object> configurationDetails) {
        BrakeTestConfigurationPage.navigateHereFromLoginPageAsMotTest(driver, login, vehicle)
                .enterBrakeConfigurationPageFields(configurationDetails).cancel()
                .cancelMotTest(reasonToCancel).clickLogout();
    }

    @Test(groups = {"slice_A", "VM-4447", "VM-4225"})
    public void testEnterNoDetailsForClass1And2Vehicle() {
        BrakeTestConfigurationPage brakeTestConfigurationPage = BrakeTestConfigurationPage
                .navigateHereFromLoginPageAsMotTest(driver, login,
                        createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002))
                .enterBrakeConfigurationPageFields(BrakeTestConfiguration1And2
                        .brakeTestConfigClasses1And2_NO_FIELDS_POPULATED()).submitExpectingError();
        assertThat("The validation message", ValidationSummary.isValidationSummaryDisplayed(driver),
                is(true));
        brakeTestConfigurationPage.cancel().cancelMotTest(reasonToCancel).clickLogout();
    }

    @Test(groups = {"slice_A", "VM-4447", "VM-4225"})
    public void testEnterNoDetailsForClass3Vehicle() {
        BrakeTestConfigurationPage brakeTestConfigurationPage = BrakeTestConfigurationPage
                .navigateHereFromLoginPageAsMotTest(driver, login,
                        createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011))
                .enterBrakeConfigurationPageFields(
                        BrakeTestConfiguration3.brakeTestConfigClass3_NO_FIELDS_POPULATED())
                .submitExpectingError();
        assertThat("The validation message", ValidationSummary.isValidationSummaryDisplayed(driver),
                is(true));
        brakeTestConfigurationPage.cancel().cancelMotTest(reasonToCancel).clickLogout();
    }

    @Test(groups = {"slice_A", "VM-4447", "VM-4225"})
    public void testEnterNoDetailsForClass4Vehicle() {
        BrakeTestConfigurationPage brakeTestConfigurationPage = BrakeTestConfigurationPage
                .navigateHereFromLoginPageAsMotTest(driver, login,
                        createVehicle(Vehicle.VEHICLE_CLASS4_MONDEO_2002))
                .enterBrakeConfigurationPageFields(
                        BrakeTestConfiguration4.brakeTestConfigClass4_NO_FIELDS_POPULATED())
                .submitExpectingError();
        assertThat("The validation message", ValidationSummary.isValidationSummaryDisplayed(driver),
                is(true));
        brakeTestConfigurationPage.cancel().cancelMotTest(reasonToCancel).clickLogout();
    }

    @Test(groups = {"slice_A", "VM-1029", "VM-988", "VM-987"})
    public void testEnterNoDetailsAndClickNext() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924);
        BrakeTestConfigurationPage.navigateHereFromLoginPageAsMotTest(driver, login, vehicle)
                .submitExpectingError();

        assertThat(ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

    }

    @DataProvider(name = "submitConfigurationDetailsProvider")
    public Object[][] submitConfigurationDetailsProvider() {
        return new Object[][] {{createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011),
                BrakeTestConfiguration3.brakeTestConfigClass3_CASE1()},
                {createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011),
                        BrakeTestConfiguration3.brakeTestConfigClass3_RollerAndGradient()},
                {createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011),
                        BrakeTestConfiguration3.brakeTestConfigClass3_RollerAndDecelerometer()},
                {createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011),
                        BrakeTestConfiguration3.brakeTestConfigClass3_DecelerometerAndRoller()},
                {createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011),
                        BrakeTestConfiguration3.brakeTestConfigClass3_DecelerometerAndGradient()},
                {createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011), BrakeTestConfiguration3
                        .brakeTestConfigClass3_DecelerometerAndDecelerometer()},
                {createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011),
                        BrakeTestConfiguration3.brakeTestConfigClass3_PlateAndPlate()},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        BrakeTestConfiguration4.brakeTestConfigClass4_Roller()},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        BrakeTestConfiguration4.brakeTestConfigClass4_RollerAndDecelerometer()},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        BrakeTestConfiguration4.brakeTestConfigClass4_RollerAndGradient()},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        BrakeTestConfiguration4.brakeTestConfigClass4_PlateAndPlate()},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        BrakeTestConfiguration4.brakeTestConfigClass4_PlateAndDecelerometer()},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        BrakeTestConfiguration4.brakeTestConfigClass4_PlateAndGradient()},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        BrakeTestConfiguration4.brakeTestConfigClass4_DecelerometerAndRoller()},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004), BrakeTestConfiguration4
                        .brakeTestConfigClass4_DecelerometerAndDecelerometer()},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        BrakeTestConfiguration4.brakeTestConfigClass4_DecelerometerAndGradient()},
                {createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924),
                        BrakeTestConfiguration5.brakeTestConfigClass5_Roller()},
                {createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924),
                        BrakeTestConfiguration5.brakeTestConfigClass5_RollerAndDecelerometer()},
                {createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924),
                        BrakeTestConfiguration5.brakeTestConfigClass5_RollerAndGradient()},
                {createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924),
                        BrakeTestConfiguration5.brakeTestConfigClass5_DecelerometerAndRoller()},
                {createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924), BrakeTestConfiguration5
                        .brakeTestConfigClass5_DecelerometerAndDecelerometer()},
                {createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924),
                        BrakeTestConfiguration5.brakeTestConfigClass5_DecelerometerAndGradient()},
                {createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924),
                        BrakeTestConfiguration5.editBrakeTestConfigClass5_Roller()},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_Roller()},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_RollerAndDecelerometer()},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_RollerAndGradient()},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_PlateAndPlate()},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_PlateAndDecelerometer()},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_PlateAndGradient()},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_DecelerometerAndRoller()},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005), BrakeTestConfiguration7
                        .brakeTestConfigClass7_DecelerometerAndDecelerometer()},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_DecelerometerAndGradient()},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_CASE3()},
                {createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002),
                        BrakeTestConfiguration1And2.brakeTestConfigClasses1And2_CASE1()},
                {createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002),
                        BrakeTestConfiguration1And2.brakeTestConfigClasses1And2_PLATE()},
                {createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002),
                        BrakeTestConfiguration1And2.brakeTestConfigClasses1And2_DECELEROMETER()},
                {createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002),
                        BrakeTestConfiguration1And2.brakeTestConfigClasses1And2_FLOOR()},
                {createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002),
                        BrakeTestConfiguration1And2.brakeTestConfigClasses1And2_GRADIENT()}};
    }

    @DataProvider(name = "invalidCalculatedWeightProvider")
    public Object[][] notApplicableWeightNoValueProvider() {
        return new Object[][] {{createVehicle(Vehicle.VEHICLE_CLASS3_PIAGGIO_2011),
                BrakeTestConfiguration3.brakeTestConfigClass3_INVALIDWEIGHT(), null},
                {createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004),
                        BrakeTestConfiguration4.brakeTestConfigClass4_INVALIDWEIGHT(),
                        BrakeTestConfigurationPageField.VEHICLE_WEIGHT},
                {createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924),
                        BrakeTestConfiguration5.brakeTestConfigClass5_INVALIDWEIGHT(),
                        BrakeTestConfigurationPageField.VEHICLE_WEIGHT},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_INVALIDWEIGHT(),
                        BrakeTestConfigurationPageField.VEHICLE_WEIGHT},};
    }

    @Test(groups = {"slice_A", "VM-1029", "VM-1343", "VM-986",
            "VM-987"}, dataProvider = "notApplicableWeightNoValueProvider")
    public void testInvalidCalculatedWeight(Vehicle vehicle,
            Map<BrakeTestConfigurationPageField, Object> configurationDetails,
            BrakeTestConfigurationPageField fieldMarkedAsInvalid) {
        BrakeTestConfigurationPage brakeTestConfigurationPage = BrakeTestConfigurationPage
                .navigateHereFromLoginPageAsMotTest(driver, login, vehicle)
                .enterBrakeConfigurationPageFields(configurationDetails).submitExpectingError();
        if (fieldMarkedAsInvalid != null) {
            assertThat("The validation message",
                    ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
        }
        brakeTestConfigurationPage.cancel().cancelMotTest(reasonToCancel).clickLogout();
    }

    @DataProvider(name = "weightEnteredNoTypeSelectedProvider")
    public Object[][] profanityDescriptionProvider() {
        return new Object[][] {{createVehicle(Vehicle.VEHICLE_CLASS5_STREETKA_1924),
                BrakeTestConfiguration5.brakeTestConfigClass5_WEIGHTTYPE_NOTSELECTED(),
                BrakeTestConfigurationPageField.VEHICLE_WEIGHT_DGW_MAM},
                {createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005),
                        BrakeTestConfiguration7.brakeTestConfigClass7_WithoutWeightType(),
                        BrakeTestConfigurationPageField.VEHICLE_WEIGHT_DGW}};
    }

    @Test(groups = {"VM-1029", "VM-986"}, dataProvider = "weightEnteredNoTypeSelectedProvider")
    public void testWeightEnteredNoTypeSelected(Vehicle vehicle,
            Map<BrakeTestConfigurationPageField, Object> configurationDetails,
            BrakeTestConfigurationPageField fieldMarkedAsInvalid) {
        BrakeTestConfigurationPage brakeTestConfigurationPage = BrakeTestConfigurationPage
                .navigateHereFromLoginPageAsMotTest(driver, login, vehicle)
                .enterBrakeConfigurationPageFields(configurationDetails).submitExpectingError();
        Assert.assertTrue(brakeTestConfigurationPage.isElementMarkedInvalid(fieldMarkedAsInvalid));
        brakeTestConfigurationPage.cancel().cancelMotTest(reasonToCancel).clickLogout();
    }

    @DataProvider(name = "invalidWeightProvider") public Object[][] invalidWeightProvider() {
        return new Object[][] {{createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002),
                BrakeTestConfiguration1And2.brakeTestConfigClasses1And2_INVALIDWEIGHT()},
                {createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002), BrakeTestConfiguration1And2
                        .brakeTestConfigClasses1And2_INVALID_WEIGHT_REAR()},};
    }

    @Test(groups = {"VM-1029", "VM-989", "VM-987"}, dataProvider = "invalidWeightProvider")
    public void testInvalidVehicleWeight(Vehicle vehicle,
            Map<BrakeTestConfigurationPageField, Object> configurationDetails) {
        BrakeTestConfigurationPage brakeTestConfigurationPage = BrakeTestConfigurationPage
                .navigateHereFromLoginPageAsMotTest(driver, login, vehicle)
                .enterBrakeConfigurationPageFields(configurationDetails).submitExpectingError();
        assertThat("The validation message", ValidationSummary.isValidationSummaryDisplayed(driver),
                is(true));
        brakeTestConfigurationPage.cancel().cancelMotTest(reasonToCancel).clickLogout();
    }

    @Test(groups = {"VM-1029", "VM-989", "VM-987"}) public void testInvalidRiderWeight() {
        BrakeTestConfigurationPage brakeTestConfigurationPage = BrakeTestConfigurationPage
                .navigateHereFromLoginPageAsMotTest(driver, login,
                        createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002))
                .enterBrakeConfigurationPageFields(BrakeTestConfiguration1And2
                        .brakeTestConfigClasses1And2_INVALID_WEIGHT_RIDER()).submitExpectingError();
        assertThat("The validation message", ValidationSummary.isValidationSummaryDisplayed(driver),
                is(true));
        brakeTestConfigurationPage.cancel().cancelMotTest(reasonToCancel).clickLogout();
    }

    @Test(groups = {"VM-4225"}) public void testInvalidWeightsForClass1And2Vehicle() {
        BrakeTestConfigurationPage brakeTestConfigurationPage = BrakeTestConfigurationPage
                .navigateHereFromLoginPageAsMotTest(driver, login,
                        createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002))
                .enterBrakeConfigurationPageFields(BrakeTestConfiguration1And2
                        .brakeTestConfigClasses1And2_FLOOR_INVALID_WEIGHTS())
                .submitExpectingError();
        assertThat("The validation message", ValidationSummary.isValidationSummaryDisplayed(driver),
                is(true));
        brakeTestConfigurationPage.cancel().cancelMotTest(reasonToCancel).clickLogout();
    }

    @Test(groups = {"VM-1029", "VM-989", "VM-987"}) public void testValidSidecarWeight() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002);
        BrakeTestConfigurationPage.navigateHereFromLoginPageAsMotTest(driver, login, vehicle)
                .enterBrakeConfigurationPageFields(
                        BrakeTestConfiguration1And2.brakeTestConfigClasses1And2_WITH_SIDECAR())
                .submit().cancel().cancelMotTest(reasonToCancel).clickLogout();
    }

    @Test(groups = {"VM-2193", "VM-2190", "VM-3182", "Sprint 23", "Mot Testing"})
    public void testPresentBrakeWeightHistory() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);
        TestSummary testSummary = TestSummary
                .navigateHereFromLoginPage(driver, login, vehicle, "12000",
                        BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.allPass(), null, null, null, null);
        testSummary.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint().clickLogout();
        BrakeTestConfigurationPage brakeTestConfigurationPage = BrakeTestConfigurationPage
                .navigateHereFromLoginPageAsMotTest(driver, login, vehicle);
        Assert.assertEquals(brakeTestConfigurationPage
                        .valueOfWeightField(BrakeTestConfigurationPageField.VEHICLE_WEIGHT), "500",
                "The VTR is not the same as the history or is missing");
        brakeTestConfigurationPage.cancel().cancelMotTest(reasonToCancel).clickLogout();

    }

    @Test(groups = {"VM-2193", "VM-2190", "VM-3182", "Sprint 23", "Mot Testing"})
    public void testPresentedWeightDoesNotOverwriteVTR() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);
        TestSummary testSummary = TestSummary
                .navigateHereFromLoginPage(driver, login, vehicle, "12000",
                        BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.allPass(), null, null, null, null);
        testSummary.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint().clickLogout();
        testSummary = TestSummary.navigateHereFromLoginPage(driver, login, vehicle, "12100",
                BrakeTestConfiguration4.brakeTestConfigClass4_CASE2(), BrakeTestResults4.allPass(),
                null, null, null, null);
        testSummary.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint().clickLogout();
        BrakeTestConfigurationPage brakeTestConfigurationPage = BrakeTestConfigurationPage
                .navigateHereFromLoginPageAsMotTest(driver, login, vehicle);
        Assert.assertEquals(brakeTestConfigurationPage
                        .valueOfWeightField(BrakeTestConfigurationPageField.VEHICLE_WEIGHT), "500",
                "Presented weight has changed the weight in the VTR");
        brakeTestConfigurationPage.cancel().cancelMotTest(reasonToCancel).clickLogout();
    }

    @Test(groups = {"VM-8767", "Mot Testing", "slice_A"})
    public void testClass4VehicleWithWeightNotApplicable() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);
        TestSummary testSummary = TestSummary
                .navigateHereFromLoginPage(driver, login, vehicle, "12000",
                        BrakeTestConfiguration4.brakeTestConfigClass4_CASE3(),
                        BrakeTestResults4.allPass(), null, null, null, null);
        testSummary.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint().clickLogout();
    }
}
