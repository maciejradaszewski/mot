package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResultsPageField;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestSummary4;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.framework.api.MotTestApi;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EnforcementMotTestSummaryPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EnforcementTestComparisonPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages.BrakeTestSummaryPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.*;
import org.joda.time.DateTime;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import java.util.*;

import static com.dvsa.mot.selenium.datasource.ReasonForRejection.EMISSIONS_EXEMPT_FROM_EMISSIONS_TESTING_END_DATED;
import static com.dvsa.mot.selenium.framework.Utilities.convertBase64ToString;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;
import static org.testng.Assert.assertTrue;

public class ReasonForRejection2Test extends BaseTest {

    private AeService aeService = new AeService();

    private String createMotTestWithRfr(ReasonForRejection rfr, Vehicle vehicle, Login login,
            Site site) {

        List<ReasonForRejection> rfrs = new ArrayList<>();
        rfrs.add(rfr);

        MotTestApi.MotTestData testData =
                new MotTestApi.MotTestData(MotTestApi.TestOutcome.FAILED, 1234, DateTime.now());

        testData.setRfrs(rfrs);

        return new MotTestApi().createTest(login, vehicle, site.getId(), testData, null);
    }


    @Test(groups = {"Regression", "VM-1581", "VM-1578", "VM-1579", "short-vehicle", "VM-1741"})
    public void addManualAdvisoryWithProfanityDescriptionAndPressCancelAndAddSuccessfully() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        String profanityDescription = "YSQkaG9sZSQ=";
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle);
        int previousNumberOfAdvisories = motTestPage.getNumberOfAdvisories();
        ReasonForRejectionPage reasonForRejectionPage = motTestPage.addRFR();
        ManualAdvisoryPage manualAdvisoryPage = reasonForRejectionPage.addManualyAdvisor();
        manualAdvisoryPage.enterManualAdvisory(ManualAdvisory.manualAdvisory_CASE1)
                .clearDescription().enterDescription(convertBase64ToString(profanityDescription))
                .addManualAdvisoryExpectingError();

        assertThat("Check error message", manualAdvisoryPage.getErrorMessages(),
                is(Assertion.ASSERTION_PROFANITY_DETECTED.assertion));

        manualAdvisoryPage.cancelManualAdvisory().clickDone();
        int updatedNumberOfAdvisories = motTestPage.getNumberOfAdvisories();

        assertThat("Compare updated number of advisories to previous", updatedNumberOfAdvisories,
                is(previousNumberOfAdvisories));

        motTestPage.addRFR().addManualyAdvisor()
                .submitManualAdvisory(ManualAdvisory.manualAdvisory_CASE1).clickDone();
        int updatedNumberOfAdvisoriesAfterAddingRFR = motTestPage.getNumberOfAdvisories();

        assertThat("Compare updated number of advisories to previous",
                updatedNumberOfAdvisoriesAfterAddingRFR, is(previousNumberOfAdvisories + 1));
    }

    @DataProvider(name = "DP-MultipleRFRs")
    public Object[][] testClass4MOTFailWithMultipleRFRsData() {

        return new Object[][] {
                {createTester(), BrakeTestResults4.allFail(), BrakeTestSummary4.allFailResults,
                        createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004), RandomDataGenerator
                        .generateRandomNumber(5, UUID.randomUUID().hashCode())},};
    }

    @Test(enabled = true,
            description = "Fail MOT on multiple RFR's and tests add/remove of RFR items,creates PRS and creates MOT certificate.",
            groups = {"VM-1666", "VM-315", "VM-1661", "Regression", "VM-1128", "VM-1861",
                    "VM-1579"},
            dataProvider = "DP-MultipleRFRs")
    public void testClass4MOTFailOnBrakeTestAndPRSAndMultipleRFRs(Login login,
            Map<BrakeTestResultsPageField, Object> inputsBrakeFailure,
            Map<String, String> expResultBrakeFailure, Vehicle vehicle, String odometerReading) {

        LoginPage loginPage = new LoginPage(driver);


        MotTestPage motTestPage = loginPage
                .loginSearchVINandRegGoToMotTestResults(login.username, login.password,
                        vehicle.fullVIN, vehicle.carReg);

        assertThat("Displayed default number of failures is not correct",
                motTestPage.getNumberOfFailures(), is(0));
        assertThat("Displayed default number of PRS is not correct", motTestPage.getNumberOfPRS(),
                is(0));
        assertThat("Displayed default number of advisories is not correct",
                motTestPage.getNumberOfAdvisories(), is(0));

        motTestPage.addRFR().addFailure(FailureRejection.BALLJOINT_EXCESSIVELY_DETERIORATED)
                .clickDone();
        motTestPage.addRFR().addFailure(FailureRejection.BRAKE_LININGS_LESS_THAN_1_5_THICK)
                .clickDone();
        motTestPage.addRFR().addFailure(
                FailureRejection.VEHICLES_1ST_USE_AFTER_2_JANUARY_TREAD_DEPTH_BELOW_REQUIREMENTS)
                .clickDone();
        motTestPage.addRFR().addFailure(FailureRejection.HORN_CONTROL_MISSING).clickDone();
        motTestPage.addRFR().addFailure(FailureRejection.HORN_CONTROL_INSECURE).clickDone();

        //Assert no of RFRs is 5
        assertThat("Assert number of RFR failures.", motTestPage.getNumberOfFailures(), is(5));

        //Remove RFR
        motTestPage.expandAndShowFailures().removeRfR();

        //Assert no of RFRs is 4
        assertThat("Assert number of RFR failures.", motTestPage.getNumberOfFailures(), is(4));

        //Add PRS
        motTestPage.addPRS(PRSrejection.HORN_CONTROL_INSECURE);

        assertThat("Assert number of PRS items.", motTestPage.getNumberOfPRS(), is(1));

        //Enter odometer/ brake test results
        motTestPage.enterOdometerValuesAndSubmit(odometerReading).addBrakeTest()
                .enterBrakeConfigurationPageFields(
                        BrakeTestConfiguration4.brakeTestConfigClass4_CASE1()).submit()
                .enterBrakeResultsPageFields(inputsBrakeFailure).submit();

        BrakeTestSummaryPage brakeTestSummary = new BrakeTestSummaryPage(driver);

        //Compare actual brake results with expected values
        assertThat("Compare actual brake results with expected values",
                brakeTestSummary.getResultsMap(), is(expResultBrakeFailure));

        //Click Done & Create Certificate
        TestSummary testSummaryPage = brakeTestSummary.clickDoneButton().createCertificate();

        //Assert RFR Failures on Test Summary page
        assertThat("Assert RFR Failures on Test Summary page", testSummaryPage.getRfrDetails()
                        .contains(
                                FailureRejection.BRAKE_LININGS_LESS_THAN_1_5_THICK.reason.reasonDescription),
                is(true));
        assertThat("Assert RFR Failures on Test Summary page", testSummaryPage.getRfrDetails()
                        .contains(FailureRejection.HORN_CONTROL_MISSING.reason.reasonDescription),
                is(true));
        assertTrue(testSummaryPage.getRfrDetails()
                .contains(FailureRejection.HORN_CONTROL_INSECURE.reason.reasonDescription));
        assertThat("Assert RFR Failures on Test Summary page", testSummaryPage.getRfrDetails()
                        .contains(FailureRejection.HORN_CONTROL_INSECURE.reason.reasonDescription),
                is(true));
        //Assert PRS
        assertTrue(testSummaryPage.getPrsDetails()
                .contains(PRSrejection.HORN_CONTROL_INSECURE.reason.reasonDescription));
        assertThat("Assert PRS", testSummaryPage.getPrsDetails()
                .contains(PRSrejection.HORN_CONTROL_INSECURE.reason.reasonDescription), is(true));

        testSummaryPage.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint().clickDoneButton()
                .clickLogout();
    }

    @Test(groups = "Regression")
    public void testClickFailureIsDangerousOnFailSuccessfully_ManualAdvisory() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle);
        ReasonForRejectionPage reasonForRejectionPage = motTestPage.addRFR();
        ManualAdvisoryPage manualAdvisoryPage = reasonForRejectionPage.addManualyAdvisor();
        reasonForRejectionPage =
                manualAdvisoryPage.submitManualAdvisory(ManualAdvisory.manualAdvisory_CASE2);
        motTestPage = reasonForRejectionPage.clickDone();

        assertThat("Check number of advisories", motTestPage.getNumberOfAdvisories(), is(1));
    }

    @Test(groups = {"Regression", "VM-1661", "VM-1861"})
    public void testClickFailureIsDangerousOnFailSuccessfully() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .addFailure(FailureRejection.HORN_CONTROL_MISSING).expandAndShowFailures();
        motTestPage.collapseAndHiddenFailures();

        assertThat("Check number of advisories", motTestPage.getNumberOfFailures(), is(1));
    }

    @Test(groups = {"Regression", "VM-1661", "VM-1861", "short-vehicle"})
    public void testClickFailureIsDangerousOnPRSSuccessfully() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .addPRS(PRSrejection.HORN_CONTROL_MISSING).expandAndShowPRS()
                .collapseAndHiddenPRS();

        assertThat("Check number of advisories", motTestPage.getNumberOfPRS(), is(1));
    }

    @Test(groups = {"Regression", "VM-1661", "VM-1861"})
    public void testClickFailureIsDangerousOnAdvisorySuccessfully() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .addAdvisory(AdvisoryRejection.ELECTRICAL_WIRING_DAMAGE_WIRED_EXPOSED);

        assertThat("Check number of advisories", motTestPage.getNumberOfAdvisories(), is(1));
    }

    /**
     * Return a list of NonComponentDefects ids, in order to add all of them and ensure they have been added successfully
     *
     * @return
     */
    @DataProvider(name = "DP-NonComponentRelatedDefectsSuccessfully")
    public Object[][] addNonComponentDefectsProvider() {

        return new Object[][] {
                {new AdvisoryRejection[] {AdvisoryRejection.BRAKE_LININGS_LESS_THAN_1_5_THICK,
                        AdvisoryRejection.ELECTRICAL_WIRING_DAMAGE_WIRED_EXPOSED,
                        AdvisoryRejection.ELECTRICAL_WIRING_DAMAGED_CAUSE_SHORT}},
                {new AdvisoryRejection[] {AdvisoryRejection.ELECTRICAL_WIRING_DAMAGE_WIRED_EXPOSED,
                        AdvisoryRejection.ELECTRICAL_WIRING_DAMAGED_CAUSE_SHORT}},
                {new AdvisoryRejection[] {
                        AdvisoryRejection.ELECTRICAL_WIRING_DAMAGED_CAUSE_SHORT}}};
    }

    @Test(groups = {"Regression", "VM-1665", "short-vehicle"},
            dataProvider = "DP-NonComponentRelatedDefectsSuccessfully")
    public void testAddNonComponentRelatedDefectsSuccessfully(AdvisoryRejection[] advisories) {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .addAdvisories(advisories);
        int numberOfAdvisories = motTestPage.getNumberOfAdvisories();

        assertThat("Check number of advisories", numberOfAdvisories, is(advisories.length));
    }

    @Test(groups = {"Regression", "VM-1951"})
    public void testVehicleBreadcrumbAfterSelectingGeneralCategory() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        VehicleCategory c = VehicleCategory.CAT_BODY_STRUCTURE_AND_GENERAL_ITEMS;
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        ReasonForRejectionPage reasonForRejectionPage =
                MotTestPage.navigateHereFromLoginPage(driver, login, vehicle).addRFR()
                        .selectCategory(c);

        assertThat("Check description",
                reasonForRejectionPage.getBreadcrumbText().contains(c.categoryDescription),
                is(true));

        reasonForRejectionPage.selectCategoryFromBreadcrumb(VehicleCategory.PARENT_RFR_HOME);

        assertThat("Check visibility of breadcrumb", reasonForRejectionPage.isBreadcrumbVisible(),
                is(false));
    }

    @Test(groups = {"Regression", "VM-1951"})
    public void testDoorsBreadcrumbAndGoBackToGeneralCategory() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        VehicleCategory cat = VehicleCategory.CAT_BODY_STRUCTURE_AND_GENERAL_ITEMS;
        VehicleCategory subCat = VehicleCategory.SUBCAT_DOORS;
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        ReasonForRejectionPage reasonForRejectionPage =
                MotTestPage.navigateHereFromLoginPage(driver, login, vehicle).addRFR()
                        .selectCategory(cat).selectCategory(subCat);

        assertThat("Check category",
                reasonForRejectionPage.getBreadcrumbText().contains(cat.categoryDescription),
                is(true));
        assertThat("Check sub-category",
                reasonForRejectionPage.getBreadcrumbText().contains(subCat.categoryDescription),
                is(true));

        reasonForRejectionPage.selectCategoryFromBreadcrumb(cat);

        assertThat("Check category",
                reasonForRejectionPage.getBreadcrumbText().contains(subCat.categoryDescription),
                is(false));
    }

    @Test(groups = {"Regression", "VM-1584", "VM-1589"})
    public void testCanAddManualRFRInMotRetest() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        // create new mot with one manual failure
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .addMotTest("2000", BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.allFail(), null, null, null, null);
        MotTestPage motTestPage = new MotTestPage(driver);
        TestSummary summaryPage = motTestPage.createCertificate();
        String certNo = summaryPage.getMotTestNumber();

        MOTRetestPage motRetestPage =
                summaryPage.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint()
                        .clickDoneButton().startMotRetest()
                        .submitSearchWithPreviousTestNumber(certNo).startTest();
        int previousNumberOfFailures = motRetestPage.getNumberOfFailures();
        motRetestPage.addFailure(FailureRejection.HORN_CONTROL_MISSING);

        assertThat("Compare the number of current failures with previous",
                motRetestPage.getNumberOfFailures(), is(previousNumberOfFailures + 1));
    }

    @Test(groups = {"Regression", "VM-1584", "VM-1589"})
    public void testCanRectifyFailureInMotRetest() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        FailureRejection[] failures =
                new FailureRejection[] {FailureRejection.HORN_CONTROL_INSECURE};
        // create new mot with one manual failure
        MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .addMotTest("2000", BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.allFail(), failures, null, null, null);
        MotTestPage motTestPage = new MotTestPage(driver);
        TestSummary summaryPage = motTestPage.createCertificate();
        String certNo = summaryPage.getMotTestNumber();
        MOTRetestPage motRetestPage =
                summaryPage.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint()
                        .clickDoneButton().startMotRetest()
                        .submitSearchWithPreviousTestNumber(certNo).startTest();
        int previousNumberOfFailures = motTestPage.getNumberOfFailures();
        motRetestPage.expandAndShowFailures().removeRfR();

        assertThat("Compare the number of current failures with previous",
                motRetestPage.getNumberOfFailures(), is(previousNumberOfFailures - 1));
    }

    @Test(groups = {"Regression", "VM-1584", "VM-1589"})
    public void testCanRectifyPRSInMotRetest() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        PRSrejection[] rejections = {PRSrejection.HORN_CONTROL_MISSING};
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        // create new mot with one manual failure
        MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .addMotTest("2000", BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.allFail(), null, rejections, null, null);
        MotTestPage motTestPage = new MotTestPage(driver);
        TestSummary summaryPage = motTestPage.createCertificate();
        String certNo = summaryPage.getMotTestNumber();
        MOTRetestPage motRetestPage =
                summaryPage.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint()
                        .clickDoneButton().startMotRetest()
                        .submitSearchWithPreviousTestNumber(certNo).startTest();

        assertThat("Check the number of PRS", motRetestPage.getNumberOfPRS(), is(0));
    }

    @Test(groups = {"Regression", "VM-1584", "VM-1589"}) public void testCanAddPRSInMotRetest() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        // create new mot with one manual failure
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .addMotTest("2000", BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.allFail(), null, null, null, null);
        MotTestPage motTestPage = new MotTestPage(driver);
        TestSummary summaryPage = motTestPage.createCertificate();
        String certNo = summaryPage.getMotTestNumber();
        MOTRetestPage motRetestPage =
                summaryPage.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint()
                        .clickDoneButton().startMotRetest()
                        .submitSearchWithPreviousTestNumber(certNo).startTest();
        int previousNumberOfPRS = motRetestPage.getNumberOfPRS();
        motRetestPage.addPRS(PRSrejection.HORN_CONTROL_INSECURE);

        assertThat("Cheˆck the number of PRS", motRetestPage.getNumberOfPRS(),
                is(previousNumberOfPRS + 1));
    }

    @Test(groups = {"Regression", "VM-1584", "VM-1589"})
    public void testCanRectifyAdvisoryInMotRetest() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        AdvisoryRejection[] advisories = {AdvisoryRejection.ELECTRICAL_WIRING_DAMAGE_WIRED_EXPOSED};
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        // create new mot with one manual failure
        MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .addMotTest("2000", BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.allFail(), null, null, advisories, null);
        MotTestPage motTestPage = new MotTestPage(driver);
        TestSummary summaryPage = motTestPage.createCertificate();
        String certNo = summaryPage.getMotTestNumber();
        MOTRetestPage motRetestPage =
                summaryPage.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint()
                        .clickDoneButton().startMotRetest()
                        .submitSearchWithPreviousTestNumber(certNo).startTest();
        int previousNumberOfAdvisories = motTestPage.getNumberOfAdvisories();
        motRetestPage.expandAndShowAdvisories().removeRfR();

        assertThat("Compare the number of current failures with previous",
                motRetestPage.getNumberOfAdvisories(), is(previousNumberOfAdvisories - 1));
    }

    @Test(groups = {"Regression", "VM-1581", "VM-1584", "VM-1589"})
    public void testCanAddAdvisoryInMotRetest() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        // create new mot with one manual failure
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .addMotTest("2000", BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.allFail(), null, null, null, null);
        MotTestPage motTestPage = new MotTestPage(driver);
        TestSummary summaryPage = motTestPage.createCertificate();
        String certNo = summaryPage.getMotTestNumber();
        MOTRetestPage motRetestPage =
                summaryPage.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint()
                        .clickDoneButton().startMotRetest()
                        .submitSearchWithPreviousTestNumber(certNo).startTest();
        int previousNumberOfAdvisories = motRetestPage.getNumberOfAdvisories();
        motRetestPage.addAdvisory(AdvisoryRejection.ELECTRICAL_WIRING_DAMAGED_CAUSE_SHORT);

        assertThat("Check the number of advisories", motRetestPage.getNumberOfAdvisories(),
                is(previousNumberOfAdvisories + 1));
    }

    @Test(groups = {"Regression", "VM-5137", "VM-4901"})
    public void endDatedRfrIsVisibleOnDuplicateCertificatePage() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BMW_ALPINA_29);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        String motTestNumber =
                createMotTestWithRfr(EMISSIONS_EXEMPT_FROM_EMISSIONS_TESTING_END_DATED, vehicle,
                        login, site);

        DuplicateReplacementCertificatePage searchResultPage = DuplicateReplacementCertificatePage
                .navigateHereFromLoginPage(driver, login, vehicle);
        MOTTestResultPage summaryPage = searchResultPage.clickViewByMOTNumber(motTestNumber);

        assertThat("Check failure contains the right reason for rejection", summaryPage
                        .failuresContain(
                                EMISSIONS_EXEMPT_FROM_EMISSIONS_TESTING_END_DATED.reasonDescription),
                is(true));
    }

    @DataProvider(name = "itemsNotFound") public Object[][] itemsNotFound() {

        return new Object[][] {{"9151"}, {"& "}};
    }

    @Test(groups = {"Regression", "VM-5139", "VM-4901"}, dataProvider = "itemsNotFound")
    public void testItemsThatShouldNotReturnResults(String item) {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BMW_ALPINA_29);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login, vehicle);
        ReasonForRejectionPage rfrPage = motTestPage.addRFR();
        rfrPage.searchForRfr(String.valueOf(item));

        assertThat("End dated RFR and '&' should not return results",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
    }

    @Test(groups = {"Regression", "VM-5136", "VM-4901"})
    public void testEndDatedRFRIsVisibleOnOldMOTest() {

        AeDetails aeDetails = aeService.createAe(
                RandomDataGenerator.generateRandomAlphaNumeric(12, UUID.randomUUID().hashCode()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BMW_ALPINA_29);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login login = createTester(Arrays.asList(site.getId()));

        // create mot test with end dated rfr
        String motNum =
                createMotTestWithRfr(EMISSIONS_EXEMPT_FROM_EMISSIONS_TESTING_END_DATED, vehicle,
                        login, site);

        // Login as a VE and search for old mot test
        LoginPage loginPage = new LoginPage(driver);
        loginPage.loginAsUser(Login.LOGIN_ENFTESTER);
        EnforcementTestComparisonPage enforcementTestComparisonPage =
                new EnforcementTestComparisonPage(driver);
        enforcementTestComparisonPage.clickCompareMOTestLink();
        enforcementTestComparisonPage.newSelectDropdown("Site (recent tests)");
        enforcementTestComparisonPage.searchByVTS(site.getNumber(), motNum, vehicle);
        EnforcementMotTestSummaryPage enfTestSummaryPage =
                new EnforcementMotTestSummaryPage(driver);

        assertThat("Check failure contains the right reason for rejection", enfTestSummaryPage
                        .failuresContain(
                                EMISSIONS_EXEMPT_FROM_EMISSIONS_TESTING_END_DATED.reasonDescription),
                is(true));
    }
}
