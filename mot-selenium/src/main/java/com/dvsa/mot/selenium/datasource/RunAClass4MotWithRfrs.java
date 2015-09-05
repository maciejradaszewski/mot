package com.dvsa.mot.selenium.datasource;

import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfigurationPageField;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResultsPageField;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.TestSummary;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.VehicleSearchPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.support.PageFactory;

import java.util.Map;

public class RunAClass4MotWithRfrs {

    private final WebDriver driver;

    public RunAClass4MotWithRfrs(WebDriver driver) {
        PageFactory.initElements(driver, this);
        this.driver = driver;
    }

    public void runReInspectionMotTestWithSingleRfr(
            Map<BrakeTestConfigurationPageField, Object> configEntries,
            Map<BrakeTestResultsPageField, Object> resultEntries, String title) {
        FailureRejection[] failures = {FailureRejection.BALLJOINT_EXCESSIVELY_DETERIORATED};

        MotTestPage motTestPage = new MotTestPage(driver, title);
        motTestPage.addMotTest("12345", configEntries, resultEntries, failures, null, null, null,
                title).createCertificate();//Click on Review


    }

    public void runReInspectionMotTestWithNoRfr(
            Map<BrakeTestConfigurationPageField, Object> configEntries,
            Map<BrakeTestResultsPageField, Object> resultEntries, String title) {
        FailureRejection[] failures = {FailureRejection.BRAKE_LININGS_LESS_THAN_1_5_THICK};

        MotTestPage motTestPage = new MotTestPage(driver, title);
        motTestPage.addMotTest("12345", configEntries, resultEntries, failures, null, null, null,
                title).createCertificate();//Click on Review
        //  .clickFinishPrint();
        //  ReInspectionCompletePage testComplete = new ReInspectionCompletePage(driver);
        //testComplete.clickCompareTestResults();

    }

    public void runReInspectionMotTestwithSitIdORLocation(
            Map<BrakeTestConfigurationPageField, Object> configEntries,
            Map<BrakeTestResultsPageField, Object> resultEntries, String title) {
        FailureRejection[] failures = {FailureRejection.BALLJOINT_EXCESSIVELY_DETERIORATED};

        MotTestPage motTestPage = new MotTestPage(driver, title);
        motTestPage.addMotTest("12345", configEntries, resultEntries, failures, null, null, null,
                title).createCertificate();
        //.clickFinishPrint();
        //  ReInspectionCompletePage testComplete = new ReInspectionCompletePage(driver);
        // testComplete.clickCompareTestResults();

    }

    public UserDashboardPage runMotClass4TestWithRfrs(Login login, Vehicle vehicleInfo) {
        return runMotClass4TestWithRfrs(
                MotTestPage.navigateHereFromLoginPage(driver, login, vehicleInfo));
    }

    public UserDashboardPage runMotClass4TestWithRfrsAsMultipleVtsTester(Login login, Site site,
            Vehicle vehicleInfo) {
        return runMotClass4TestWithRfrs(
                VehicleSearchPage.navigateHereFromLoginPageForManyVtsTester(driver, login, site)
                        .submitSearch(vehicleInfo).startTest());
    }

    private UserDashboardPage runMotClass4TestWithRfrs(MotTestPage motTestPage) {
        FailureRejection[] failures = {FailureRejection.BALLJOINT_EXCESSIVELY_DETERIORATED_WONR,
                FailureRejection.BRAKE_LININGS_LESS_THAN_1_5_THICK,
                FailureRejection.VEHICLES_1ST_USE_AFTER_2_JANUARY_TREAD_DEPTH_BELOW_REQUIREMENTS};
        return motTestPage.addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(),
                BrakeTestResults4.enforcement_CASE1(), failures, null, null, null)
                .createCertificate().enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint()
                .clickBackToHomeLink();

    }

    public UserDashboardPage runMotClass4TestWithPRSFailAdvisoryRfrs(Login login,
            Vehicle vehicleInfo) {
        FailureRejection[] failures = {FailureRejection.BALLJOINT_EXCESSIVELY_DETERIORATED};
        AdvisoryRejection[] advisories = {AdvisoryRejection.BRAKE_LININGS_LESS_THAN_1_5_THICK};
        PRSrejection[] prs =
                {PRSrejection.VEHICLES_1ST_USE_AFTER_2_JANUARY_TREAD_DEPTH_BELOW_REQUIREMENTS};

        return MotTestPage.navigateHereFromLoginPage(driver, login, vehicleInfo)
                .addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(),
                        BrakeTestResults4.enforcement_CASE1(), failures, prs, advisories, null)
                .createCertificate().enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint()
                .clickBackToHomeLink();
    }

    public UserDashboardPage runMotClass4TestWithSingleRfr(Login login, Vehicle vehicleInfo) {
        FailureRejection[] failures = {FailureRejection.BALLJOINT_EXCESSIVELY_DETERIORATED_WONR};

        return MotTestPage.navigateHereFromLoginPage(driver, login, vehicleInfo)
                .addMotTest("12346", BrakeTestConfiguration4.enforcement_CASE1(),
                        BrakeTestResults4.enforcement_CASE1(), failures, null, null, null)
                .createCertificate().enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint()
                .clickBackToHomeLink();
    }

    public UserDashboardPage runMotClass4TestWithPRSFailAdvisoryRfrs2(Login login,
            Vehicle vehicleInfo) {
        FailureRejection[] failures = {FailureRejection.BALLJOINT_EXCESSIVELY_DETERIORATED};
        AdvisoryRejection[] advisories = {AdvisoryRejection.BRAKE_LININGS_LESS_THAN_1_5_THICK};
        PRSrejection[] prs =
                {PRSrejection.VEHICLES_1ST_USE_AFTER_2_JANUARY_TREAD_DEPTH_BELOW_REQUIREMENTS};

        return MotTestPage.navigateHereFromLoginPage(driver, login, vehicleInfo)
                .addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(),
                        BrakeTestResults4.enforcement_CASE1(), failures, prs, advisories, null)
                .createCertificate().enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint()
                .clickBackToHomeLink();
    }

    public TestSummary runMotClass4TestWithAdvisoryRfrs(Login login, Vehicle vehicleInfo){
        AdvisoryRejection[] advisories =
                {AdvisoryRejection.BRAKE_LININGS_LESS_THAN_1_5_THICK,
                        AdvisoryRejection.ELECTRICAL_WIRING_DAMAGE_WIRED_EXPOSED,
                        AdvisoryRejection.ELECTRICAL_WIRING_DAMAGED_CAUSE_SHORT,
                        AdvisoryRejection.BALLJOINT_EXCESSIVELY_DETERIORATED,
                        AdvisoryRejection.ELECTRICAL_WIRING_DETERIORATED_CAUSE_SHORT,
                        AdvisoryRejection.DOOR_PILLAR_DETERIORATED,
                        AdvisoryRejection.VEHICLES_1ST_USE_AFTER_2_JANUARY_TREAD_DEPTH_BELOW_REQUIREMENTS,
                        AdvisoryRejection.BREAK_CONTROL_SHORT,
                        AdvisoryRejection.DOOR_PILLAR_DETERIORATED,
                        AdvisoryRejection.ROAD_WHEELS_WORN_HUB_SPIGOT,
                        AdvisoryRejection.SERIOUSLY_DAMAGED,
                        AdvisoryRejection.HOSES_MODIFIED,
                        AdvisoryRejection.EXCESSIVELY_WEAKENED_CORROSION,
                        AdvisoryRejection.BRACKET_MODIFIED_WEAKENED,
                        AdvisoryRejection.TREAD_DEPTH_BELOW_1_0,
                        AdvisoryRejection.WARNING_LAMP_INDICATES_FAULT,
                        AdvisoryRejection.AFFECTED_OPERATION_ANOTHER_LAMP,
                        AdvisoryRejection.SERIOUSLY_MISALIGNED,
                        AdvisoryRejection.WIRES_BROKEN_SIGNIFICANTLY_WEAKENED,
                        AdvisoryRejection.DIAL_GLASS_BROKEN,
                        AdvisoryRejection.BRACKET_EXCESSIVELY_WORN,
                        AdvisoryRejection.TOP_BEARING_EXCESSIVELY_WORN,
                        AdvisoryRejection.SLIPPER_EXCESSIVELY_WORN,
                        AdvisoryRejection.EXCESSIVE_PLAY_RECEIVER_SOCKET,
                        AdvisoryRejection.DAMAGED_LEAD_CANNOT_BE_SECURELY_CONNECTED,
                        AdvisoryRejection.DETERIORATED_CANNOT_FUNCTION};

        return MotTestPage.navigateHereFromLoginPage(driver, login, vehicleInfo)
                .addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(),
                        BrakeTestResults4.enforcement_CASE1(), null, null, advisories, null)
                .createCertificate()
                .enterNewPasscode(Text.TEXT_PASSCODE);
    }

    public TestSummary runWelshMotClass4TestWithAdvisoryRfrs(Login login, Vehicle vehicleInfo, Site site){
        AdvisoryRejection[] advisories =
                {AdvisoryRejection.BRAKE_LININGS_LESS_THAN_1_5_THICK,
                        AdvisoryRejection.ELECTRICAL_WIRING_DAMAGE_WIRED_EXPOSED,
                        AdvisoryRejection.ELECTRICAL_WIRING_DAMAGED_CAUSE_SHORT,
                        AdvisoryRejection.BALLJOINT_EXCESSIVELY_DETERIORATED,
                        AdvisoryRejection.ELECTRICAL_WIRING_DETERIORATED_CAUSE_SHORT,
                        AdvisoryRejection.DOOR_PILLAR_DETERIORATED,
                        AdvisoryRejection.VEHICLES_1ST_USE_AFTER_2_JANUARY_TREAD_DEPTH_BELOW_REQUIREMENTS};

        return MotTestPage.navigateHereFromLoginPage(driver, login, vehicleInfo, site)
                .addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(), BrakeTestResults4.enforcement_CASE1(), null, null, advisories, null)
                .createCertificate()
                .enterNewPasscode(Text.TEXT_PASSCODE);
    }

}
