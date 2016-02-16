package com.dvsa.mot.selenium.priv.frontend.enforcement.tests;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.MotTestApi;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.*;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import org.testng.Assert;
import org.testng.annotations.Test;

import java.util.Collections;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class CreateReInspectionOutcomeReportTests extends BaseTest {

    private void loginAsEnforcementAndClickOnMOTLink() {
        Login login = createVE();
        LoginPage loginPage = new LoginPage(driver);
        loginPage.loginAsEnforcementUser(login);
        EnforcementHomePage homePage = new EnforcementHomePage(driver);
        homePage.goToVtsNumberEntryPage();
    }

    private void loginAsTesterActAndLogout(Vehicle vehicle, Login login) {
        RunAClass4MotWithRfrs runMotTest = new RunAClass4MotWithRfrs(driver);
        runMotTest.runMotClass4TestWithSingleRfr(login, vehicle).clickLogout();
    }

    @Test(groups = {"VM-1845", "Sprint17", "Enf", "Regression"})
    public void complaintNumberBoxDisplayed() {
        Login login = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        RunAClass4MotWithRfrs runMotTest = new RunAClass4MotWithRfrs(driver);
        runMotTest.runMotClass4TestWithSingleRfr(login, vehicle).clickLogout();

        loginAsEnforcementAndClickOnMOTLink();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(BusinessDetails.BUSINESS_DETAILS_9.vtsNo);
        lpVtsSearch.clickSearch();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        resultsScreen.selectSummaryLinkFromTable(login, vehicle);
        EnforcementMotTestSummaryPage summaryPage = new EnforcementMotTestSummaryPage(driver);
        summaryPage.setTypeOfTest(Text.TEXT_ENF_INVERTED_APPEAL.text);
        Assert.assertTrue(summaryPage.checkComplaintRefTextBoxDisplayed(),"Verify complaint number box is displayed");
        summaryPage.setTypeOfTest(Text.TEXT_ENF_TARGETED_RE_INSPECTION.text);
        Assert.assertEquals(summaryPage.getTypeOfTest(), Text.TEXT_ENF_TARGETED_RE_INSPECTION.text,"Verify Target Re-inspection test type is selected");
        if(!summaryPage.checkComplaintRefTextBoxDisplayed())
            Assert.assertTrue(true,"Verify complaint number box is not displayed");
        summaryPage.clickLogout();
    }

    @Test(groups = {"VM-1845", "Sprint17", "Enf", "Regression"})
    public void complaintNumberNotEntered() {
        Login login = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        RunAClass4MotWithRfrs runMotTest = new RunAClass4MotWithRfrs(driver);
        runMotTest.runMotClass4TestWithSingleRfr(login, vehicle).clickLogout();

        loginAsEnforcementAndClickOnMOTLink();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(BusinessDetails.BUSINESS_DETAILS_9.vtsNo);
        lpVtsSearch.clickSearch();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        resultsScreen.selectSummaryLinkFromTable(login, vehicle);
        EnforcementMotTestSummaryPage summaryPage = new EnforcementMotTestSummaryPage(driver);
        summaryPage.setTypeOfTest(Text.TEXT_ENF_INVERTED_APPEAL.text);
        summaryPage.startInspectionInvertedAppeal();
        Assert.assertTrue(summaryPage.checkComplaintRefTextBoxDisplayed(),
                "Summary page is still displayed as needed");
        summaryPage.clickLogout();
    }

    @Test(groups = {"VM-2079", "Sprint17", "Enf", "Regression"})
    public void verifyRfrComparisonLabels() {
        Login login = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        RunAClass4MotWithRfrs runMotTest = new RunAClass4MotWithRfrs(driver);
        runMotTest.runMotClass4TestWithPRSFailAdvisoryRfrs(login, vehicle).clickLogout();

        loginAsEnforcementAndClickOnMOTLink();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(BusinessDetails.BUSINESS_DETAILS_9.vtsNo);
        lpVtsSearch.clickSearch();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        resultsScreen.selectSummaryLinkFromTable(login, vehicle);
        EnforcementMotTestSummaryPage summaryPage = new EnforcementMotTestSummaryPage(driver);
        summaryPage.startInspection();
        runMotTest.runReInspectionMotTestWithNoRfr(BrakeTestConfiguration4.enforcement_CASE1(),
                BrakeTestResults4.enforcement_CASE1(),
                PageTitles.MOT_REINSPECTION_PAGE.getPageTitle());
        MotTestSummaryPage motTestSummaryPage = new MotTestSummaryPage(driver);
        motTestSummaryPage.enterSearchCriteria(BusinessDetails.BUSINESS_DETAILS_10.vtsNo);
        motTestSummaryPage.clickFinishTest();
        motTestSummaryPage.clickCompareResults();
        EnforcementTestComparisonPage comparisonPage = new EnforcementTestComparisonPage(driver);
        comparisonPage.addText2JustificationBox1(Text.TEXT_TESTER_JUSTIFICATION);
        comparisonPage.addText2JustificationBox2(Text.TEXT_TESTER_JUSTIFICATION);
        comparisonPage.addText2VeJustificationBox1(Text.TEXT_VE_JUSTIFICATION);
        comparisonPage.addText2JustificationBox4(Text.TEXT_TESTER_JUSTIFICATION);
        comparisonPage.clickRecordAssessmentButton();
        AssessmentDetailsConfirmationPage assessmentDetailsConfirmationPage =
                new AssessmentDetailsConfirmationPage(driver);
        Assert.assertEquals(assessmentDetailsConfirmationPage.getContentTitle(),
                Assertion.ASSERTION_ASSESSMENT_CONFIRMATION_PAGE_TITLE.assertion,
                "Verify the assessment details confirmation page title");
        Assert.assertEquals(assessmentDetailsConfirmationPage.getSuccessMsg(),
                Assertion.ASSERTION_ASSESSMENT_CONFIRMATION_SUCCESS_MESSAGE.assertion,
                "Verify the assessment details confirmation page success message");
        assessmentDetailsConfirmationPage.clickLogout();
    }

    // TODO rewrite test after prior investigation
    @Test(groups = {"VM-1407", "Sprint17", "Enf", "VM-2952", "Sprint25", "Enf", "Regression"}, enabled = false)
    public void invertedAppealReportWithRfr() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Login login = createTester();
        loginAsTesterActAndLogout(vehicle, login);
        loginAsEnforcementAndClickOnMOTLink();
        search4VTS();

        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        resultsScreen.selectSummaryLinkFromTable(login, vehicle);
        EnforcementMotTestSummaryPage summaryPage = new EnforcementMotTestSummaryPage(driver);
        summaryPage.setTypeOfTest(Text.TEXT_ENF_INVERTED_APPEAL.text);
        summaryPage.enterComplaintReferenceNumber(Text.TEXT_ENF_COMPLAINT_REFERENCE_NUMBER.text);
        summaryPage.startInspection();

        RunAClass4MotWithRfrs runMotTest = new RunAClass4MotWithRfrs(driver);
        runMotTest.runReInspectionMotTestWithSingleRfr(BrakeTestConfiguration4.enforcement_CASE1(),
                BrakeTestResults4.enforcement_CASE1(),
                PageTitles.MOT_REINSPECTION_PAGE.getPageTitle());
        MotTestSummaryPage motTestSummaryPage = new MotTestSummaryPage(driver);
        motTestSummaryPage.enterSearchCriteria(BusinessDetails.BUSINESS_DETAILS_10.vtsNo);
        Assert.assertFalse(motTestSummaryPage.verifyexpiryDate(),"Verify expiry date is displayed");
        motTestSummaryPage.clickFinishTest();
        motTestSummaryPage.clickCompareResults();
        EnforcementTestComparisonPage comparisonPage = new EnforcementTestComparisonPage(driver);
        comparisonPage.selectDropdown(
                RunTargetedReInspection.xPathStringsUsedForComparrison.NTSCOREDROPDOWN,
                Text.TEXT_DISREGARD.text);
        comparisonPage.addTextToNtJustificationBox(Text.TEXT_TESTER_JUSTIFICATION);
        comparisonPage.selectDropdown(
                RunTargetedReInspection.xPathStringsUsedForComparrison.VESCOREDROPDOWN, Text.TEXT_DISREGARD.text);
        comparisonPage.addTextToVeJustificationBox(Text.TEXT_VE_JUSTIFICATION);
        comparisonPage.clickRecordAssessmentButton();
        AssessmentDetailsConfirmationPage assessmentDetailsConfirmationPage =
                new AssessmentDetailsConfirmationPage(driver);
        Assert.assertEquals(assessmentDetailsConfirmationPage.getContentTitle(),
                Assertion.ASSERTION_ASSESSMENT_CONFIRMATION_PAGE_TITLE.assertion,
                "Verify the assessment details confirmation page title");
        Assert.assertEquals(assessmentDetailsConfirmationPage.getSuccessMsg(),
                Assertion.ASSERTION_ASSESSMENT_CONFIRMATION_SUCCESS_MESSAGE.assertion,
                "Verify the assessment details confirmation page success message");
        assessmentDetailsConfirmationPage.clickLogout();
    }

    @Test(groups = {"VM-1407", "Sprint17", "Enf", "VM-2952", "Sprint25", "Enf","Regression"})
    public void invertedAppealReportWithNoRfr() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Login login = createTester();
        loginAsTesterActAndLogout(vehicle, login);
        loginAsEnforcementAndClickOnMOTLink();
        search4VTS();

        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        resultsScreen.selectSummaryLinkFromTable(login, vehicle);
        EnforcementMotTestSummaryPage summaryPage = new EnforcementMotTestSummaryPage(driver);
        summaryPage.setTypeOfTest(Text.TEXT_ENF_INVERTED_APPEAL.text);
        summaryPage.enterComplaintReferenceNumber(Text.TEXT_ENF_COMPLAINT_REFERENCE_NUMBER.text);
        summaryPage.startInspection();

        RunAClass4Mot runMotTest = new RunAClass4Mot(driver);
        runMotTest.runAReInspectionMotTestPass(PageTitles.MOT_REINSPECTION_PAGE.getPageTitle());
        MotTestSummaryPage motTestSummaryPage = new MotTestSummaryPage(driver);
        motTestSummaryPage.enterSearchCriteria(BusinessDetails.BUSINESS_DETAILS_10.vtsNo);
        Assert.assertTrue(motTestSummaryPage.verifyexpiryDate(),"Verify expiry date is displayed");
        assertThat(Text.TEXT_MOT_TEST_EXPIRY_DATE.text, motTestSummaryPage.getExpiryDate(),
                is(motTestSummaryPage.getExpiryDateFromIssueDate()));
        motTestSummaryPage.clickFinishTest();
        motTestSummaryPage.clickCompareResults();
        EnforcementTestComparisonPage comparisonPage = new EnforcementTestComparisonPage(driver);
        comparisonPage.selectDropdown(
                RunTargetedReInspection.xPathStringsUsedForComparrison.NTSCOREDROPDOWN,
                Text.TEXT_DISREGARD.text);
        comparisonPage.addTextToNtJustificationBox(Text.TEXT_TESTER_JUSTIFICATION);
        comparisonPage.clickRecordAssessmentButton();
        AssessmentDetailsConfirmationPage assessmentDetailsConfirmationPage =
                new AssessmentDetailsConfirmationPage(driver);
        Assert.assertEquals(assessmentDetailsConfirmationPage.getContentTitle(),
                Assertion.ASSERTION_ASSESSMENT_CONFIRMATION_PAGE_TITLE.assertion,
                "Verify the assessment details confirmation page title");
        Assert.assertEquals(assessmentDetailsConfirmationPage.getSuccessMsg(),
                Assertion.ASSERTION_ASSESSMENT_CONFIRMATION_SUCCESS_MESSAGE.assertion,
                "Verify the assessment details confirmation page success message");
        assessmentDetailsConfirmationPage.clickLogout();
    }

    @Test(groups = {"VM-2952", "Sprint25", "Enf", "Regression"})
    public void statutoryAppealReportWithSingleRfr() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Login login = createTester();
        loginAsTesterActAndLogout(vehicle, login);
        loginAsEnforcementAndClickOnMOTLink();
        search4VTS();

        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        resultsScreen.selectSummaryLinkFromTable(login, vehicle);
        EnforcementMotTestSummaryPage summaryPage = new EnforcementMotTestSummaryPage(driver);
        summaryPage.setTypeOfTest(Text.TEXT_ENF_STATUTORY_APPEAL.text);
        summaryPage.enterComplaintReferenceNumber(Text.TEXT_ENF_COMPLAINT_REFERENCE_NUMBER.text);
        summaryPage.startInspection();

        RunAClass4MotWithRfrs runMotTest = new RunAClass4MotWithRfrs(driver);
        runMotTest.runReInspectionMotTestWithSingleRfr(BrakeTestConfiguration4.enforcement_CASE1(),
                BrakeTestResults4.enforcement_CASE1(),
                PageTitles.MOT_REINSPECTION_PAGE.getPageTitle());
        MotTestSummaryPage motTestSummaryPage = new MotTestSummaryPage(driver);
        motTestSummaryPage.enterSearchCriteria(Text.TEXT_VTS);
        Assert.assertFalse(motTestSummaryPage.verifyexpiryDate(),"Verify expiry date is displayed");
        motTestSummaryPage.clickFinishTest();
        motTestSummaryPage.clickCompareResults();
        EnforcementTestComparisonPage comparisonPage = new EnforcementTestComparisonPage(driver);
        comparisonPage.selectDropdown(
                RunTargetedReInspection.xPathStringsUsedForComparrison.NTSCOREDROPDOWN,
                Text.TEXT_DISREGARD.text);
        comparisonPage.addTextToNtJustificationBox(Text.TEXT_TESTER_JUSTIFICATION);
        comparisonPage.selectDropdown(
                RunTargetedReInspection.xPathStringsUsedForComparrison.VESCOREDROPDOWN,
                Text.TEXT_DISREGARD.text);
        comparisonPage.addTextToVeJustificationBox(Text.TEXT_VE_JUSTIFICATION);
        comparisonPage.clickRecordAssessmentButton();
        AssessmentDetailsConfirmationPage assessmentDetailsConfirmationPage =
                new AssessmentDetailsConfirmationPage(driver);
        Assert.assertEquals(assessmentDetailsConfirmationPage.getContentTitle(),
                Assertion.ASSERTION_ASSESSMENT_CONFIRMATION_PAGE_TITLE.assertion,
                "Verify the assessment details confirmation page title");
        Assert.assertEquals(assessmentDetailsConfirmationPage.getSuccessMsg(),
                Assertion.ASSERTION_ASSESSMENT_CONFIRMATION_SUCCESS_MESSAGE.assertion,
                "Verify the assessment details confirmation page success message");
        assessmentDetailsConfirmationPage.clickLogout();
    }

    @Test(groups = {"VM-2952", "Sprint25", "Enf","Regression"}) public void statutoryAppealReportWithNoRfr() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Login login = createTester();
        loginAsTesterActAndLogout(vehicle, login);
        loginAsEnforcementAndClickOnMOTLink();
        search4VTS();

        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        resultsScreen.selectSummaryLinkFromTable(login, vehicle);
        EnforcementMotTestSummaryPage summaryPage = new EnforcementMotTestSummaryPage(driver);
        summaryPage.setTypeOfTest(Text.TEXT_ENF_STATUTORY_APPEAL.text);
        summaryPage.enterComplaintReferenceNumber(Text.TEXT_ENF_COMPLAINT_REFERENCE_NUMBER.text);
        summaryPage.startInspection();

        RunAClass4Mot runMotTest = new RunAClass4Mot(driver);
        runMotTest.runAReInspectionMotTestPass(PageTitles.MOT_REINSPECTION_PAGE.getPageTitle());
        MotTestSummaryPage motTestSummaryPage = new MotTestSummaryPage(driver);
        motTestSummaryPage.enterSearchCriteria(BusinessDetails.BUSINESS_DETAILS_10.vtsNo);
        Assert.assertTrue(motTestSummaryPage.verifyexpiryDate(),"Verify expiry date is displayed");
        assertThat(Text.TEXT_MOT_TEST_EXPIRY_DATE.text, motTestSummaryPage.getExpiryDate(),
                is(motTestSummaryPage.getExpiryDateFromIssueDate()));
        motTestSummaryPage.clickFinishTest();
        motTestSummaryPage.clickCompareResults();
        EnforcementTestComparisonPage comparisonPage = new EnforcementTestComparisonPage(driver);
        comparisonPage.selectDropdown(
                RunTargetedReInspection.xPathStringsUsedForComparrison.NTSCOREDROPDOWN,
                Text.TEXT_DISREGARD.text);
        comparisonPage.addTextToNtJustificationBox(Text.TEXT_TESTER_JUSTIFICATION);
        comparisonPage.clickRecordAssessmentButton();
        AssessmentDetailsConfirmationPage assessmentDetailsConfirmationPage =
                new AssessmentDetailsConfirmationPage(driver);
        Assert.assertEquals(assessmentDetailsConfirmationPage.getContentTitle(),
                Assertion.ASSERTION_ASSESSMENT_CONFIRMATION_PAGE_TITLE.assertion,
                "Verify the assessment details confirmation page title");
        Assert.assertEquals(assessmentDetailsConfirmationPage.getSuccessMsg(),
                Assertion.ASSERTION_ASSESSMENT_CONFIRMATION_SUCCESS_MESSAGE.assertion,
                "Verify the assessment details confirmation page success message");
        assessmentDetailsConfirmationPage.clickLogout();
    }

    @Test(groups = {"VM-2556", "Sprint 21", "Enf", "Regression"})
    public void enterSiteIdAndLocationDisable() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        int vtsId =
                createVTS(createAE("VM-2556"), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, "VM-2556");
        Site site = new Site(vtsId, null, "V12345", null, null);
        Login login = createTester(Collections.singleton(vtsId));
        createMotTest(login, site, vehicle, 12345, MotTestApi.TestOutcome.FAILED);
        EnforcementHomePage.navigateHereFromLoginPage(driver, createVE()).clickMOTLink()
                .selectDropdown(Text.TEXT_ENF_REGISTRATION_SEARCH)
                .enterSearchCriteria(vehicle.carReg).clickSearch();
        TestHistoryPage testHistoryPage = new TestHistoryPage(driver);
        testHistoryPage.clickOnViewFailResultTest();

        EnforcementMotTestSummaryPage enforcementMotTestSummaryPage =
                new EnforcementMotTestSummaryPage(driver);
        enforcementMotTestSummaryPage.startInspection()

                .enterOdometerValuesAndSubmit("12345",
                        PageTitles.MOT_REINSPECTION_TEST_ENTRY_PAGE.getPageTitle())
                .addNewBrakeTest(BrakeTestConfiguration4.enforcement_CASE1(),
                        BrakeTestResults4.brakeTestEntry_CASE1(),
                        PageTitles.MOT_REINSPECTION_TEST_ENTRY_PAGE.getPageTitle())
                .createCertificate();
        MotTestSummaryPage motTestSummaryPage = new MotTestSummaryPage(driver);
        motTestSummaryPage.enterSearchCriteria(site.getNumber());
        Assert.assertFalse(motTestSummaryPage.isLocationEnabled(),
                "Assert location dropdown is disabled");
        motTestSummaryPage.clickFinishTest().clickLogout();
    }

    @Test(groups = {"VM-2556", "Sprint 21", "Enf", "Test 02", "Regression"},
            description = "Enter the valid VTS number and assure Location is disabled")
    public void enterLocationSiteIdAndOnePersonTest() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        int vtsId = createVTS(createAE("VM2556-02"), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                "VM-2556-02");
        Login login = createTester(Collections.singleton(vtsId));
        loginAsTesterActAndLogout(vehicle, login);
        EnforcementHomePage.navigateHereFromLoginPage(driver, createVE()).clickMOTLink()
                .selectDropdown(Text.TEXT_ENF_REGISTRATION_SEARCH)
                .enterSearchCriteria(vehicle.carReg).clickSearch();
        runReInspection(login, vehicle);
        RunAClass4MotWithRfrs runMotTest = new RunAClass4MotWithRfrs(driver);
        runMotTest.runReInspectionMotTestwithSitIdORLocation(
                BrakeTestConfiguration4.enforcement_CASE1(), BrakeTestResults4.enforcement_CASE1(),
                PageTitles.MOT_REINSPECTION_PAGE.getPageTitle());
        MotTestSummaryPage motTestSummaryPage =
                new MotTestSummaryPage(driver).enterLocation("Bristol");
        Assert.assertTrue(motTestSummaryPage.isSitedisabled(), "Site Id is disabled");
        motTestSummaryPage.clickFinishTest();
        motTestSummaryPage.clickLogout();
    }

    @Test(groups = {"VM-1755", "Sprint22", "Enf","Regression"})
    public void verifyDefectCategory() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Login login = createTester();
        RunAClass4MotWithRfrs runMotTest = new RunAClass4MotWithRfrs(driver);
        runMotTest.runMotClass4TestWithSingleRfr(login, vehicle).clickLogout();

        loginAsEnforcementAndClickOnMOTLink();
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(BusinessDetails.BUSINESS_DETAILS_9.vtsNo);
        lpVtsSearch.clickSearch();
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        resultsScreen.selectSummaryLinkFromTable(login, vehicle);
        EnforcementMotTestSummaryPage summaryPage = new EnforcementMotTestSummaryPage(driver);
        summaryPage.setTypeOfTest(Text.TEXT_ENF_MOT_COMPLIANCE_SURVEY.text);
        summaryPage.startInspection();
        runMotTest.runReInspectionMotTestWithSingleRfr(BrakeTestConfiguration4.enforcement_CASE1(),
                BrakeTestResults4.enforcement_CASE1(),
                PageTitles.MOT_REINSPECTION_PAGE.getPageTitle());
        MotTestSummaryPage motTestSummaryPage = new MotTestSummaryPage(driver);
        motTestSummaryPage.clickFinishTest();
        motTestSummaryPage.clickCompareResults();

        EnforcementTestComparisonPage comparisonPage = new EnforcementTestComparisonPage(driver);
        comparisonPage.selectDropdown(
                RunTargetedReInspection.xPathStringsUsedForComparrison.NTSCOREDROPDOWN,
                Text.TEXT_ENF_CUT_DOWN_SCORE_TWENTY_NO_DEFECT.text);
        comparisonPage.selectDropdown(
                RunTargetedReInspection.xPathStringsUsedForComparrison.NTDEFECTDECISIONS,
                Text.TEXT_INCORRECT_DECISION.text);
        Assert.assertFalse(comparisonPage.verifyCategory(),"Verify category is disabled");
        Assert.assertEquals(comparisonPage.getCategoryText(), Text.TEXT_NOT_APPLICABLE.text,
                "Verify category text");
    }

    @Test(groups = {"VM-2556", "Sprint 21", "VM-1825", "VM-2938", "Sprint 22", "Enf", "Test 03","Regression"})
    public void enterLocationSiteDetailsSummaryReport() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Login login = createTester();
        loginAsTesterActAndLogout(vehicle, login);
        loginAsEnforcementAndClickOnMOTLink();
        search4VTS();
        runReInspection(login, vehicle);
        RunAClass4MotWithRfrs runMotTest = new RunAClass4MotWithRfrs(driver);
        runMotTest.runReInspectionMotTestwithSitIdORLocation(
                BrakeTestConfiguration4.enforcement_CASE1(), BrakeTestResults4.enforcement_CASE1(),
                PageTitles.MOT_REINSPECTION_PAGE.getPageTitle());
        MotTestSummaryPage motTestSummaryPage = new MotTestSummaryPage(driver);
        motTestSummaryPage.enterLocation("Bristol");
        motTestSummaryPage.clickFinishTest();
        motTestSummaryPage.clickCompareResults();
    }



    private void runReInspection(Login login, Vehicle vehicle) {
        VtsRecentResultsPage resultsScreen = new VtsRecentResultsPage(driver);
        resultsScreen.selectSummaryLinkFromTable(login, vehicle);
        EnforcementMotTestSummaryPage summaryPage = new EnforcementMotTestSummaryPage(driver);
        summaryPage.startInspection();
    }

    private void search4VTS() {
        VtsNumberEntryPage lpVtsSearch = new VtsNumberEntryPage(driver);
        lpVtsSearch.enterVTSNumber(BusinessDetails.BUSINESS_DETAILS_9.vtsNo);
        lpVtsSearch.clickSearch();
        lpVtsSearch.waitForViewLink();
    }


}
