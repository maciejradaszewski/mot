package com.dvsa.mot.selenium.priv.frontend.enforcement.tests;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.Utilities;
import com.dvsa.mot.selenium.framework.api.MotTestApi;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.TesterCreationApi;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EnforcementHomePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.MOTTestsPopupVerificationPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import org.joda.time.DateTime;
import org.testng.Assert;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;

public class MOTTestsPopupVerificationTests extends BaseTest {
    private MOTSearchDetails historySearchDetails;

    public void setUpTestHistory() {
        if (null != historySearchDetails) {
            return;
        }

        int aeId = createAE("AE_" + getTestClassName());
        String siteName = "VTS_" + getTestClassName();
        Site site = new VtsCreationApi()
                .createVtsSite(aeId, TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, siteName);
        Login tester = new TesterCreationApi().createTester(site.getId());
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BOXSTER_2001);

        DateTime issuedDate = DateTime.now().minusDays(10);
        createMotTest(tester, site, vehicle, 14000, MotTestApi.TestOutcome.PASSED, issuedDate);

        historySearchDetails =
                new MOTSearchDetails(site.getNumber(), tester.username, vehicle.carReg,
                        vehicle.fullVIN);
    }

    private void LoginAsEnfUserMOT() {
        LoginPage loginPage = new LoginPage(driver);
        EnforcementHomePage homePage = loginPage.loginAsEnforcementUser(Login.LOGIN_ENFTESTER);
        homePage.goToVtsNumberEntryPage();
    }

    //Verify popup for "Site (recent tests)" type
    @Test(groups = {"VM-2677", "Sprint21", "Enf", "VM-2900", "Sprint21", "Enf", "VM-3118",
            "Sprint23", "Enf", "test1", "Regression"}) public void verifyPopupRecentTests() {
        setUpTestHistory();
        LoginAsEnfUserMOT();

        MOTTestsPopupVerificationPage motPopUpVerification =
                new MOTTestsPopupVerificationPage(driver);

        motPopUpVerification.selectType(Text.TEXT_ENF_SITE_SEARCH);
        motPopUpVerification.enterSearchText(historySearchDetails.site);
        motPopUpVerification.search();
        motPopUpVerification.waitForViewLink();

        Assert.assertTrue(motPopUpVerification.getTitle().equals("MOT Test History"));
        Assert.assertTrue(motPopUpVerification.verifySummaryColumn());
        Assert.assertTrue(motPopUpVerification.verifyRegistrationColumn());
        Assert.assertTrue(motPopUpVerification.verifyTypeColumn());
        Assert.assertTrue(motPopUpVerification.verifyUserIdColumn());
        Assert.assertTrue(motPopUpVerification.verifyPopup());

        motPopUpVerification.logout();
    }

    //Verify popup for "Site(by date range)" type
    @Test(groups = {"VM-2677", "Sprint21", "Enf", "VM-2900", "Sprint21", "Enf", "VM-3118",
            "Sprint23", "Enf", "test2", "Regression"}) public void verifyPopupSiteDateRange() {
        setUpTestHistory();
        LoginAsEnfUserMOT();

        MOTTestsPopupVerificationPage motPopUpVerification =
                new MOTTestsPopupVerificationPage(driver);

        motPopUpVerification.selectType(Text.TEXT_ENF_SITE_DATE_RANGE_SEARCH);
        populateDateFrom(motPopUpVerification);
        motPopUpVerification.enterSearchText(historySearchDetails.site);
        motPopUpVerification.search();

        Assert.assertTrue(motPopUpVerification.getTitle().equals("MOT Test History"));
        Assert.assertTrue(motPopUpVerification.verifyRegistrationColumn());
        Assert.assertTrue(motPopUpVerification.verifySummaryColumn());
        Assert.assertTrue(motPopUpVerification.verifyTypeColumn());
        Assert.assertTrue(motPopUpVerification.verifysiteColumn());
        Assert.assertTrue(motPopUpVerification.verifyUserIdColumn());
        Assert.assertFalse(motPopUpVerification.verifyPopup());

        motPopUpVerification.logout();
    }

    //Verify popup for "Tester (by date range)" type
    @Test(groups = {"VM-2677", "Sprint21", "Enf", "VM-2900", "Sprint21", "Enf", "VM-3118",
            "Sprint23", "Enf", "test3", "VM-2932", "Sprint24", "Enf", "Regression"})
    public void verifyPopupTesterDateRange() {
        setUpTestHistory();
        LoginAsEnfUserMOT();

        MOTTestsPopupVerificationPage motPopUpVerification =
                new MOTTestsPopupVerificationPage(driver);

        motPopUpVerification.selectType(Text.TEXT_ENF_TESTER_DATE_RANGE_SEARCH);
        populateDateFrom(motPopUpVerification);
        motPopUpVerification.enterSearchText(historySearchDetails.tester);
        motPopUpVerification.search();

        Assert.assertTrue(motPopUpVerification.getTitle().equals("MOT Test History"));
        Assert.assertTrue(motPopUpVerification.verifyRegistrationColumn());
        Assert.assertTrue(motPopUpVerification.verifySummaryColumn());
        Assert.assertTrue(motPopUpVerification.verifyTypeColumn());
        Assert.assertTrue(motPopUpVerification.verifysiteColumn());
        Assert.assertFalse(motPopUpVerification.verifyPopup());

        motPopUpVerification.logout();
    }

    //Verify popup for "Registration" type
    @Test(groups = {"VM-2677", "Sprint21", "Enf", "VM-2900", "Sprint21", "Enf", "VM-3118",
            "Sprint23", "Enf", "test4", "Regression"}) public void verifyPopupRegistrationType() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        createMotTest(login, Site.POPULAR_GARAGES, vehicle, 13000, MotTestApi.TestOutcome.PASSED);
        LoginAsEnfUserMOT();

        MOTTestsPopupVerificationPage motPopUpVerification =
                new MOTTestsPopupVerificationPage(driver);

        motPopUpVerification.selectType(Text.TEXT_ENF_REGISTRATION_SEARCH);
        motPopUpVerification.enterSearchText(vehicle.carReg);
        motPopUpVerification.search();

        //Assert.assertTrue(motPopUpVerification.getTitle().equals("MOT Test History"));
        Assert.assertEquals(motPopUpVerification.getTitle(), "MOT Test History");

        Assert.assertTrue(motPopUpVerification.verifySummaryColumn());
        //Assert.assertTrue(motPopUpVerification.verifyRegistrationColumn());
        Assert.assertTrue(motPopUpVerification.verifyTypeColumn());
        Assert.assertTrue(motPopUpVerification.verifysiteColumn());
        Assert.assertTrue(motPopUpVerification.verifyUserIdColumn());
        Assert.assertFalse(motPopUpVerification.verifyPopup());

        motPopUpVerification.logout();
    }

    @Test(groups = {"VM-3342", "Sprint24", "Enf", "Regression"})
    public void verifyReturnToResultsPage() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BOXSTER_2001);
        createMotTest(login, Site.POPULAR_GARAGES, vehicle, 14000, MotTestApi.TestOutcome.PASSED);
        LoginAsEnfUserMOT();

        MOTTestsPopupVerificationPage motPopUpVerification =
                new MOTTestsPopupVerificationPage(driver);

        motPopUpVerification.selectType(Text.TEXT_ENF_REGISTRATION_SEARCH);
        motPopUpVerification.enterSearchText(vehicle.carReg);
        motPopUpVerification.search();

        Assert.assertEquals(motPopUpVerification.getTitle(), "MOT Test History");
        Assert.assertEquals(motPopUpVerification.getVRMTitle(),
                "MOT(s) found with registration mark \"" + vehicle.carReg + "\"");
        motPopUpVerification.clickSiteLink();
        motPopUpVerification.clickreturnToResultsLink();

        Assert.assertEquals(motPopUpVerification.getTitle(), "MOT Test History");
        Assert.assertEquals(motPopUpVerification.getVRMTitle(),
                "MOT(s) found with registration mark \"" + vehicle.carReg + "\"");

        motPopUpVerification.logout();
    }

    //Verify popup for "VIN/Chasis" type
    @Test(groups = {"VM-2677", "Sprint21", "Enf", "VM-2900", "Sprint21", "Enf", "VM-3118",
            "Sprint23", "Enf", "test5", "Regression"}) public void verifyPopupVINChasisType() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BOXSTER_2001);
        createMotTest(login, Site.POPULAR_GARAGES, vehicle, 14000, MotTestApi.TestOutcome.PASSED);
        LoginAsEnfUserMOT();

        MOTTestsPopupVerificationPage motPopUpVerification =
                new MOTTestsPopupVerificationPage(driver);

        motPopUpVerification.selectType(Text.TEXT_ENF_VIN_SEARCH);
        motPopUpVerification.enterSearchText(vehicle.fullVIN);
        motPopUpVerification.search();

        Assert.assertEquals(motPopUpVerification.getTitle(), "MOT Test History");
        Assert.assertTrue(motPopUpVerification.verifySummaryColumn());
        Assert.assertTrue(motPopUpVerification.verifyRegistrationColumn());
        Assert.assertTrue(motPopUpVerification.verifyTypeColumn());
        Assert.assertTrue(motPopUpVerification.verifysiteColumn());
        Assert.assertTrue(motPopUpVerification.verifyUserIdColumn());

        Assert.assertFalse(motPopUpVerification.verifyPopup());

        motPopUpVerification.logout();
    }

    //Verify title for MOT tests for type "Tester"
    @Test(groups = {"VM-2748", "Sprint21", "Enf", "VM-2900", "Sprint21", "Enf", "test1", "Regression"})
    public void verifyMOTTestsTesterTitle() {
        setUpTestHistory();
        LoginAsEnfUserMOT();

        MOTTestsPopupVerificationPage motPopUpVerification =
                new MOTTestsPopupVerificationPage(driver);
        motPopUpVerification.selectType(Text.TEXT_ENF_TESTER_DATE_RANGE_SEARCH);
        populateDateFrom(motPopUpVerification);
        motPopUpVerification.enterSearchText(historySearchDetails.tester);
        motPopUpVerification.search();

        Assert.assertEquals(motPopUpVerification.getTitle(), "MOT Test History");
        assertThat(motPopUpVerification.getTesterTitle(),
                containsString("MOT tests found for Tester"));

        motPopUpVerification.logout();
    }

    private void populateDateFrom(MOTTestsPopupVerificationPage motPopUpVerification) {
        DateTime previousMountsDate = Utilities.getPreviousMonthsDate();

        motPopUpVerification.clearMonth1();
        motPopUpVerification.enterMonth1(previousMountsDate.getMonthOfYear());
        motPopUpVerification.clearYear1();
        motPopUpVerification.enterYear1(previousMountsDate.getYear() - 1);
    }

    //Verify filter for MOT tests for type "Tester"
    @Test(groups = {"VM-2748", "Sprint21", "Enf", "VM-2900", "Sprint21", "Enf", "test2", "Regression"})
    public void verifyMOTTestsTesterFilter() {
        setUpTestHistory();
        LoginAsEnfUserMOT();

        MOTTestsPopupVerificationPage motPopUpVerification =
                new MOTTestsPopupVerificationPage(driver);

        motPopUpVerification.selectType(Text.TEXT_ENF_TESTER_DATE_RANGE_SEARCH);
        populateDateFrom(motPopUpVerification);
        motPopUpVerification.enterSearchText(historySearchDetails.tester);
        motPopUpVerification.search();

        Assert.assertEquals(motPopUpVerification.getTitle(), "MOT Test History");

        motPopUpVerification.logout();
    }
}
