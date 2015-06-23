package com.dvsa.mot.selenium.priv.frontend.enforcement.tests;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.Class4MOTData.MotResultState;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.framework.api.MotTestApi;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.*;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import org.testng.Assert;
import org.testng.annotations.Test;

import java.util.Collections;
import java.util.UUID;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

/**
 * Ian Hyndman 22/01/2014
 * Authorised Enforcement Agent searches for a vehicle testing stations details
 * by adding This test is data driven using the @DataProvider annotation
 */

public class SearchVtsForRecentTests extends BaseTest {

    /**
     * Valid test with valid VTS number
     */
    @Test(groups = {"VM-896", "Sprint11", "Enf", "Regression"})
    public void enterValidVtsNumberWithNoResults() {
        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER);
        homePage.goToVtsNumberEntryPage();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber("NON_EXISTING_VTS_NUMBER");
        lpVtsSearch.clickSearchButtonExpectingError();
        assertThat("Error message is displayed correctly", lpVtsSearch.getErrorMessage(),
                is(Text.TEXT_ENF_MOT_SEARCH_INVALID_RECENT_TESTS));
        lpVtsSearch.clickLogout();
    }

    /**
     * Valid test with valid VTS number that returns results
     */
    @Test(groups = {"VM-896", "Sprint11", "Enf", "Regression"})
    public void enterValidWholeVTSNumberAndReturnResults() {

        createMotTest(login, Site.POPULAR_GARAGES, createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010),
                50000, MotTestApi.TestOutcome.PASSED);
        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER);
        homePage.goToVtsNumberEntryPage();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(Site.POPULAR_GARAGES.getNumber());
        lpVtsSearch.clickSearchButtonExpectingError();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        assertThat("The results table is displayed", resultsScreen.isResultsTableDisplayed(),
                is(true));
        lpVtsSearch.clickLogout();
    }

    /**
     * Test to show an MOT test is in progress
     */
    @Test(groups = {"VM-896", "Sprint11", "Enf", "VM-3116", "Sprint24", "Enf", "Regression"})
    public void showTestAsInProgress() {
        Login login = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        RunAClass4Mot runMotTest = new RunAClass4Mot(driver);
        runMotTest.runMotToInProgressStage(login, vehicle).clickLogout();

        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER);
        homePage.goToVtsNumberEntryPage();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        EnforcementVTSSearchPage searchVTSPage = new EnforcementVTSSearchPage(driver);
        searchVTSPage.selectDropdown(Text.TEXT_ENF_REGISTRATION_SEARCH);
        searchVTSPage.enterSearchCriteria(vehicle.carReg);
        searchVTSPage.clickSearch();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        assertThat("The results table is displayed", resultsScreen.isResultsTableDisplayed(),
                is(true));
        Assert.assertEquals(resultsScreen.getMotTestStatusRegSearch(login, vehicle),
                Text.TEXT_RESULT_IN_PROGRESS.text, "Wrong state of test shown");

        lpVtsSearch.clickLogout();
    }

    @Test(groups = {"VM-3116", "Sprint11", "Enf", "Regression"}) public void verifyTestInProgress() {
        Login login = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        RunAClass4Mot runMotTest = new RunAClass4Mot(driver);
        runMotTest.runMotToInProgressStage(login, vehicle).clickLogout();

        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER);
        homePage.goToVtsNumberEntryPage();
        EnforcementVTSSearchPage searchVTSPage = new EnforcementVTSSearchPage(driver);
        searchVTSPage.selectDropdown(Text.TEXT_ENF_REGISTRATION_SEARCH);
        searchVTSPage.enterSearchCriteria(vehicle.carReg);
        searchVTSPage.clickSearch();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);

        Assert.assertTrue(resultsScreen.isResultsTableDisplayed());
        Assert.assertEquals(resultsScreen.getMotTestStatusRegSearch(login, vehicle),
                Text.TEXT_RESULT_IN_PROGRESS.text);
        resultsScreen.clickLogout();
    }

    /**
     * Test to show an MOT test has passed
     */
    @Test(groups = {"VM-896", "Sprint11", "Enf", "Regression"}) public void showTestAsPassed() {
        Login login = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        createMotTest(login, Site.POPULAR_GARAGES, vehicle, 12345, MotTestApi.TestOutcome.PASSED);
        LoginPage.loginAs(driver, Login.LOGIN_ENFTESTER);
        EnforcementHomePage homePage = new EnforcementHomePage(driver);
        homePage.goToVtsNumberEntryPage();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(BusinessDetails.BUSINESS_DETAILS_9.vtsNo);
        lpVtsSearch.clickSearchButtonExpectingError();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        assertThat("The results table is displayed", resultsScreen.isResultsTableDisplayed(),
                is(true));
        resultsScreen.selectFilter(vehicle.carReg);
        Assert.assertEquals(resultsScreen.getMotTestStatus(login, vehicle),
                Text.TEXT_RESULT_PASS.text);
        resultsScreen.clickLogout();
    }

    /**
     * Test to show an MOT test has failed
     */
    @Test(groups = {"VM-896", "Sprint11", "Enf", "Regression"}) public void showTestAsFailed() {
        Login login = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        createMotTest(login, Site.POPULAR_GARAGES, vehicle, 12345, MotTestApi.TestOutcome.FAILED);
        LoginPage.loginAs(driver, Login.LOGIN_ENFTESTER);
        EnforcementHomePage homePage = new EnforcementHomePage(driver);
        homePage.goToVtsNumberEntryPage();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(BusinessDetails.BUSINESS_DETAILS_9.vtsNo);
        lpVtsSearch.clickSearchButtonExpectingError();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        assertThat("The results table is displayed", resultsScreen.isResultsTableDisplayed(),
                is(true));
        resultsScreen.selectFilter(vehicle.carReg);
        Assert.assertEquals(resultsScreen.getMotTestStatus(login, vehicle),
                Text.TEXT_RESULT_FAIL.text);
        resultsScreen.clickLogout();
    }

    @Test(groups = {"VM-896", "Sprint11", "Enf", "Regression"}) public void vtsSearchAgain() {
        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER);
        homePage.goToVtsNumberEntryPage();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(BusinessDetails.BUSINESS_DETAILS_9.vtsNo);
        lpVtsSearch.clickSearchButtonExpectingError();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        assertThat("The results table is shown", resultsScreen.isResultsTableDisplayed(), is(true));
        resultsScreen.clickSearchAgain();
        VtsNumberEntryPage lpVtsSearchAgain = new VtsNumberEntryPage(driver);
        assertThat("Title is present", lpVtsSearch.pageTitleIsDisplayed(), is(true));
        lpVtsSearchAgain.clickLogout();
    }

    /**
     * Test to check that the short summary pop up is present
     */
    @Test(groups = {"VM-1015", "Sprint12", "Enf", "Regression"})
    public void shortSummaryTestForPass() {
        Login login = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        createMotTest(login, Site.POPULAR_GARAGES, vehicle, 12345, MotTestApi.TestOutcome.PASSED);
        LoginPage.loginAs(driver, Login.LOGIN_ENFTESTER);
        EnforcementHomePage homePage = new EnforcementHomePage(driver);
        homePage.goToVtsNumberEntryPage();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(BusinessDetails.BUSINESS_DETAILS_9.vtsNo);
        lpVtsSearch.clickSearchButtonExpectingError();
        lpVtsSearch.waitForViewLink();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        assertThat("The results table is displayed", resultsScreen.isResultsTableDisplayed(),
                is(true));
        resultsScreen.selectFilter(vehicle.carReg);
        resultsScreen
                .hoverOverResultLinkFromTable(MotResultState.PASS, Vehicle.VEHICLE_CLASS4_CLIO_2004,
                        Class4MOTData.motclass4_1);
        resultsScreen.clickLogout();
    }

    /**
     * Test to check that the short summary pop up is present
     */
    @Test(groups = {"VM-1015", "Sprint12", "Enf", "Regression"})
    public void shortSummaryTestForFail() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_MERCEDES_C300);
        createMotTest(createTester(), Site.POPULAR_GARAGES, vehicle, 12000,
                MotTestApi.TestOutcome.FAILED);
        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER);
        homePage.goToVtsNumberEntryPage();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(Site.POPULAR_GARAGES.getNumber());
        lpVtsSearch.clickSearchButtonExpectingError();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        Assert.assertTrue(resultsScreen.isResultsTableDisplayed(), "No results table shown");
        resultsScreen.selectFilter(vehicle.carReg);
        Assert.assertTrue(resultsScreen
                .hoverOverResultLinkFromTable(MotResultState.FAIL, Vehicle.VEHICLE_CLASS4_CLIO_2004,
                        Class4MOTData.motclass4_1));
        resultsScreen.clickLogout();
    }

    /**
     * Test to check that the short summary pop up is present
     */
    @Test(groups = {"VM-1015", "Sprint12", "Enf", "Regression"})
    public void shortSummaryTestForInProgress() {
        Login login = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        RunAClass4Mot runMotTest = new RunAClass4Mot(driver);
        runMotTest.runMotToInProgressStage(login, vehicle).clickLogout();
        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER);
        homePage.goToVtsNumberEntryPage();
        EnforcementVTSSearchPage searchVTSPage = new EnforcementVTSSearchPage(driver);
        searchVTSPage.selectDropdown(Text.TEXT_ENF_REGISTRATION_SEARCH);
        searchVTSPage.enterSearchCriteria(vehicle.carReg);
        searchVTSPage.clickSearch();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        assertThat("The results table is displayed", resultsScreen.isResultsTableDisplayed(),
                is(true));
        resultsScreen.hoverOverResultLinkFromTable(MotResultState.IN_PROGRESS,
                Vehicle.VEHICLE_CLASS4_CLIO_2004, Class4MOTData.motclass4_1);
        resultsScreen.clickLogout();
    }

    /**
     * Test to check that the short summary pop up is present
     */
    @Test(groups = {"VM-897", "Sprint12", "Enf", "Regression"}) public void showFullPassSummary() {
        Login login = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        createMotTest(login, Site.POPULAR_GARAGES, vehicle, 12345, MotTestApi.TestOutcome.PASSED);
        LoginPage.loginAs(driver, Login.LOGIN_ENFTESTER);
        EnforcementHomePage homePage = new EnforcementHomePage(driver);
        homePage.goToVtsNumberEntryPage();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(BusinessDetails.BUSINESS_DETAILS_9.vtsNo);
        lpVtsSearch.clickSearchButtonExpectingError();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        //click on the summary link of a passed test
        resultsScreen.selectFilter(vehicle.carReg);
        resultsScreen.selectSummaryLinkFromTable(login, vehicle);
        resultsScreen.clickLogout();
    }

    /**
     * Test to check that the short summary pop up is present
     */
    @Test(groups = {"VM-897", "Sprint12", "Enf", "Regression"}) public void showFullFailSummary() {

        //Login to application
        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER);
        homePage.goToVtsNumberEntryPage();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(BusinessDetails.BUSINESS_DETAILS_9.vtsNo);
        lpVtsSearch.clickSearchButtonExpectingError();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        assertThat("The results table is displayed", resultsScreen.isResultsTableDisplayed(),
                is(true));
        resultsScreen.clickLogout();
    }

    /**
     * Test to check that the short summary pop up is present
     */
    @Test(groups = {"VM-1019", "Sprint12", "Enf", "Regression"})
    public void selectTargetedReInspectionType() {

        Site site  = new VtsCreationApi().createVtsSite(createAE("selectReinspectionType"),
            TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
            RandomDataGenerator.generateRandomAlphaNumeric(20, UUID.randomUUID().hashCode()));

        createMotTest(createTester(Collections.singleton(site.getId())),
            site, createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010),
                50000, MotTestApi.TestOutcome.PASSED);
        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER);
        homePage.goToVtsNumberEntryPage();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(site.getNumber());
        lpVtsSearch.clickSearchButtonExpectingError();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        resultsScreen.selectSummaryLinkFromTable();
        EnforcementMotTestSummaryPage enfSummaryPage = new EnforcementMotTestSummaryPage(driver);
        assertThat("The dropdown selection is correct", enfSummaryPage.checkTextOfTestType(),
                is(Text.TEXT_ENF_TARGETED_RE_INSPECTION.text));
        enfSummaryPage.clickLogout();
    }
}
