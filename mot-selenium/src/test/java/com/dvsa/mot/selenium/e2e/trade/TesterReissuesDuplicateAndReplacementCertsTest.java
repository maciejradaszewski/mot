package com.dvsa.mot.selenium.e2e.trade;


import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.Colour;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.DuplicateReplacementCertificatePage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.DuplicateReplacementCertificatePrintPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MOTTestResultPage;
import org.joda.time.DateTime;
import org.joda.time.Period;
import org.testng.Assert;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import java.util.UUID;

import static com.dvsa.mot.selenium.datasource.Assertion.ASSERTION_FAIL;
import static com.dvsa.mot.selenium.datasource.Assertion.ASSERTION_PASS;
import static com.dvsa.mot.selenium.datasource.Login.LOGIN_TESTER2;
import static com.dvsa.mot.selenium.datasource.Text.TEXT_PASSCODE;
import static com.dvsa.mot.selenium.datasource.Vehicle.VEHICLE_CLASS2_CAPPUCCINO_2012;
import static com.dvsa.mot.selenium.datasource.Vehicle.VEHICLE_CLASS4_BMW_ALPINA_REISSUE_CERT;
import static com.dvsa.mot.selenium.framework.api.MotTestApi.TestOutcome;
import static com.dvsa.mot.selenium.framework.api.MotTestApi.TestOutcome.FAILED;
import static com.dvsa.mot.selenium.framework.api.MotTestApi.TestOutcome.PASSED;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;
import static org.testng.Assert.*;

public class TesterReissuesDuplicateAndReplacementCertsTest extends BaseTest {

    Site defaultSite = Site.POPULAR_GARAGES;

    @DataProvider(name = "reissueCertificateOnCurrentVTSProvider")
    public Object[][] reissueCertificateOnCurrentVTSProvider() {
        return new Object[][] {{PASSED, ASSERTION_PASS}, {FAILED, ASSERTION_FAIL},};
    }

    @Test(groups = {"slice_A", "VM-2151", "VM-2152", "E2E", "short-vehicle", "VM-2268", "VM-2269"},
            description = "Reissue Fail and Pass Duplicate Certificate on the Current VTS, and view the Duplicate certificate",
            dataProvider = "reissueCertificateOnCurrentVTSProvider")
    public void testReissueCertificateOnCurrentVTS_View(TestOutcome testOutcome,
            Assertion assertion) {

        Login tester = createTester();
        Vehicle vehicle = createVehicle(VEHICLE_CLASS4_BMW_ALPINA_REISSUE_CERT);
        String odometer = RandomDataGenerator.generateRandomNumber(5, UUID.randomUUID().hashCode());
        int odometerReading = Integer.parseInt(odometer);
        String testNumber = createMotTest(tester, defaultSite, vehicle, odometerReading, testOutcome);
        UserDashboardPage dashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, tester);
        dashboardPage.reissueCertificate()
                .submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg);
        DuplicateReplacementCertificatePage replacementCertPage =
                new DuplicateReplacementCertificatePage(driver);
        assertTrue(replacementCertPage.getTestStatus(1).equals(assertion.assertion));
        assertNotNull(replacementCertPage.getTestNumber(1));
        replacementCertPage.clickViewByMOTNumber(testNumber);
        MOTTestResultPage testResultPage = new MOTTestResultPage(driver);
        Assert.assertTrue(testResultPage.motTestStatus(), "Assert MOT Test result not displayed");
        assertEquals(testResultPage.getRegistrationNumber(), vehicle.carReg, "Assert Reg");
        assertEquals(testResultPage.getVinNumber(), vehicle.fullVIN, "Assert VIN");
        assertTrue(testResultPage.getColour().contains(vehicle.primaryColour.toString()),
                "Assert Colour");
        assertEquals(testResultPage.getMake(), vehicle.make.getVehicleMake(), "Assert Make");
        assertEquals(testResultPage.getModel(), vehicle.model.getModelName(), "Assert Model");
        assertTrue(testResultPage.getOdometerReading().contains(odometer));

        DuplicateReplacementCertificatePrintPage duplicateReplacementCertificatePrintPage =
                new DuplicateReplacementCertificatePrintPage(driver,
                        "MOT TESTING\n" + "MOT TEST RESULT");
        duplicateReplacementCertificatePrintPage.printCertificate();
        assertTrue(duplicateReplacementCertificatePrintPage.isPrintDocumentDisplayed(),
                "Check for print document button failed");
        duplicateReplacementCertificatePrintPage.clickBackToUserHome();
    }

    @Test(groups = {"slice_A", "VM-2153", "VM-2591", "VM-4511", "VM-4512", "Sprint 22", "E2E"},
            description = "Reissue fail Replacement certificate on the current VTS, editing the odometer and colour of vehicle, and then resubmitting")
    public void testReissueFailCertificateOnCurrentVTS_Edit() {
        Vehicle vehicle = createVehicle(VEHICLE_CLASS4_BMW_ALPINA_REISSUE_CERT);
        String motTest = createMotTest(login, defaultSite, vehicle, 12345, FAILED);
        DuplicateReplacementCertificatePage replacementCertPage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, login, vehicle);
        assertTrue(replacementCertPage.getTestStatus(1).equals(ASSERTION_FAIL.assertion));
        assertEquals(replacementCertPage.getTestNumber(1), motTest);
        replacementCertPage.clickFirstEditButton().submitEditedOdometerInfo("12111")
                .editColoursAndSubmit(Colour.Green, Colour.Red).reviewChangesButton()
                .enterOneTimePassword(TEXT_PASSCODE).finishAndPrintCertificate();
    }

    @Test(groups = {"VM-4512", "slice_A", "W-Sprint4", "E2E"},
            description = "Tester can only issue a replacement on the latest certificate and only with 7 days of issue, and only from their VTS.")
    public void testTesterCanNotIssueReplacementOnLatestCertificateWithMoreThan7DaysOfIssue() {
        Login tester = createTester();
        Vehicle vehicle = createVehicle(VEHICLE_CLASS2_CAPPUCCINO_2012);
        String testNumberMore7DaysIssue = createMotTest(tester, defaultSite, vehicle, 67853, FAILED,
                new DateTime().minus(Period.days(8)));
        DuplicateReplacementCertificatePage duplicateReplacementCertificatePage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, tester, vehicle);
        Assert.assertFalse(duplicateReplacementCertificatePage
                .isReplacementCertificateEditButtonDisplayed(testNumberMore7DaysIssue));
    }

}
