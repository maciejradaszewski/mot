package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.enums.Colour;
import com.dvsa.mot.selenium.datasource.enums.Days;
import com.dvsa.mot.selenium.datasource.enums.VehicleMake;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.Utilities;
import com.dvsa.mot.selenium.framework.api.MotTestApi.TestOutcome;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.VerifyCertificateDetails;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.*;
import org.joda.time.DateTime;
import org.joda.time.Period;
import org.testng.Assert;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import java.io.IOException;
import java.util.Arrays;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.Matchers.is;

public class DuplicateReplacementCertificateTest extends BaseTest {

    Site defaultSite = Site.POPULAR_GARAGES;

    @Test(priority = 1, groups = {"Regression", "VM-2153", "VM-2591", "VM-3029", "Sprint 22"},
            description = "Reissue fail certificate on the current VTS, editing the odometer and colour of vehicle, and resubmitting introducing three consecutive invalid OTP")
    public void testReissueFailCertificateOnCurrentVTS_Edit_ProvideThreeInvalidOTP() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BMW_ALPINA_REISSUE_CERT);
        createMotTest(login, defaultSite, vehicle, 13345, TestOutcome.FAILED);

        DuplicateReplacementCertificatePage replacementCertPage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, login, vehicle);

        ReplacementCertificateReviewPage replacementCertificateReviewPage =
                replacementCertPage.clickFirstEditButton().cancelEdit()
                        .submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg)
                        .clickFirstEditButton().
                        submitEditedOdometerInfo("13000")
                        .editColoursAndSubmit(Colour.Blue, Colour.Black).reviewChangesButton()
                        .enterOneTimePassword(Text.TEXT_PASSCODE_INVALID)
                        .finishAndPrintCertificateExpectingError();


        assertThat(replacementCertPage.isErrorMessageDisplayed(), is(true));

        replacementCertificateReviewPage.enterOneTimePassword(Text.TEXT_PASSCODE_INVALID)
                .finishAndPrintCertificateExpectingError();

        assertThat(replacementCertPage.isErrorMessageDisplayed(), is(true));

        replacementCertificateReviewPage.enterOneTimePassword(Text.TEXT_PASSCODE_INVALID)
                .finishAndPrintCertificateExpectingError();
        assertThat(replacementCertPage.isErrorMessageDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "VM-2151", "VM-2152", "VM-4516"},
            description = "Reissue Fail Certificate on a different VTS, and confirm")
    public void testReissueFailCertificateOnAnotherVTS_View() {
        Site site = Site.JOHNS_GARAGE;
        Login newLogin = createTester(Arrays.asList(defaultSite.getId(), site.getId()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        //Perform new MOT test in that VTS
        String testNumber = createMotTest(newLogin, site, vehicle, 123423, TestOutcome.FAILED);
        DuplicateReplacementCertificatePage.navigateHereFromLoginPage(driver, login, vehicle)
                .enterFieldsOnFirstFailTestIssuedAtOtherVTSAndSubmit(testNumber, null)
                .clickFinishButton();
    }

    @Test(groups = {"Regression", "VM-2268", "VM-2269", "VM-4515"})
    public void testPrintDocumentDuplicateAsDVSAdminUser() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        String motNumber = createMotTest(login, defaultSite, vehicle, 12345, TestOutcome.PASSED);
        DuplicateReplacementCertificatePage
                .navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1, vehicle)
                .clickViewByMOTNumber(motNumber);
    }

    @Test(groups = {"Regression", "VM-2570, VM-2571", "VM-2597", "VM-4511"},
            description = "To issue replacement test documents, DVSA Scheme Management need to be able to select a document to edit and print")
    public void testDVSAUserIssueAndEditReplacementCertificatePass() {
        DateTime expiryDate = DateTime.now().plusDays(1);
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BMW_ALPINA_REISSUE_CERT);
        createMotTest(login, defaultSite, vehicle, 12345, TestOutcome.PASSED);
        ReplacementCertificateReviewPage replacementCertificateReviewPage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1, vehicle)
                        .clickEditButtonPass()
                        .submitNoOdometerOption()
                        .editColoursAndSubmit(Colour.Black, Colour.Silver)
                        .editExpiryDateAndSubmit(DateTime.now().plusDays(1))
                        .enterReasonForReplacement("None")
                        .reviewChangesButton();
        assertThat("Edited expiry date is displayed on Replacement Certificate Review page",
                replacementCertificateReviewPage.expiryDate(),
                is(expiryDate.toString("d MMMM YYYY")));
        Assert.assertEquals(replacementCertificateReviewPage.testStatus(), (Text.TEXT_STATUS_PASS));
        Assert.assertEquals(replacementCertificateReviewPage.odometerReading(),
                (Text.TEXT_NO_ODOMETER));
    }

    @Test(groups = {"Regression", "VM-2570, VM-2571", "VM-2597", "VM-4648"},
            description = "To issue replacement test documents, DVSA Scheme Management need to be able to select a document to edit and print")
    public void testDVSAUserIssueAndEditReplacementCertificateFail() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BMW_ALPINA_REISSUE_CERT);
        createMotTest(login, defaultSite, vehicle, 13345, TestOutcome.FAILED);
        Colour PrimaryColour = Colour.Brown;
        ReplacementCertificateReviewPage replacementCertificateReviewPage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1, vehicle)
                        .clickEditButtonFail().submitOdometerNotReadableOption()
                        .editColoursAndSubmit(Colour.Brown, Colour.NoOtherColour)
                        .enterReasonForReplacement("None").reviewChangesButton();
        Assert.assertEquals(replacementCertificateReviewPage.vehicleColours(),
                PrimaryColour.getColourName());
        Assert.assertEquals(replacementCertificateReviewPage.testStatus(), (Text.TEXT_STATUS_FAIL));
        Assert.assertEquals(replacementCertificateReviewPage.odometerReading(),
                (Text.TEXT_NOT_READABLE));
    }

    @Test(enabled = true, groups = {"Regression", "VM-4346"},
            description = "As a DVSA Area Officer when I update a test location and vehicle colour on a certificate I want the update to be reflected on the review screen.")
    public void testWhenDVSAUserEditVTSAndVehicleColourOnACertificate() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BMW_ALPINA_REISSUE_CERT);
        Site vts = Site.JOHNS_MOTORCYCLE_GARAGE;
        Colour primaryColour = Colour.Bronze;
        Colour secondaryColour = Colour.Maroon;
        createMotTest(login, defaultSite, vehicle, 13345, TestOutcome.FAILED);
        ReplacementCertificateReviewPage replacementCertificateReviewPage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1, vehicle)
                        .clickEditButtonFail().editColoursAndSubmit(Colour.Bronze, Colour.Maroon)
                        .editVTSLocationAndSubmit(vts.getNumber())
                        .enterReasonForReplacement("Review").reviewChangesButton();
        assertThat("Updated VTS is not displayed in Replacement Certificate Review page",
                replacementCertificateReviewPage.getVtsName(),
                containsString(vts.getNumberAndName()));
        assertThat(
                "Updated vehicle colours are not displayed in Replacement Certificate Review page",
                replacementCertificateReviewPage.vehicleColours(),
                is(primaryColour + " and " + secondaryColour));
    }


    @Test(groups = {"VM-4355", "Regression", "W-Sprint1"},
            description = "When issuing a duplicate or replacement certificate, if the vehicle results which are returned are not the ones I need I want to be able to select to search for another vehicle")
    public void testGoToReplacementCertificatePageAndCancel() {
        DuplicateReplacementCertificatePage
                .navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1,
                        Vehicle.VEHICLE_CLASS4_ASTRA_2010).returnToReplacementSearch();
    }

    @Test(groups = {"VM-4478", "Regression", "W-Sprint2"},
            description = "As a site or DVSA user viewing test records as part of issuing a duplicate or replacement certificate I want the tests to be displayed chronologically")
    public void testTesterUserViewTestRecordsChronologicallyInDuplicateReplacementPage() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        Login tester1 = createTester();
        Site anotherSite = Site.JOHNS_GARAGE;
        Login testerOtherVTS = createTester(Arrays.asList(anotherSite.getId()));
        // Create mot test history at two VTSs
        DateTime firstTestDate = DateTime.now().minusYears(1);
        String testNumber1 =
                createMotTest(tester1, defaultSite, vehicle, 123456, TestOutcome.PASSED,
                        firstTestDate);
        String testNumberOtherVTS1 =
                createMotTest(testerOtherVTS, anotherSite, vehicle, 123479, TestOutcome.FAILED,
                        firstTestDate.plusMonths(1));
        String testNumber2 =
                createMotTest(tester1, defaultSite, vehicle, 123478, TestOutcome.FAILED,
                        firstTestDate.plusMonths(1).plusDays(1));
        String testNumberOtherVTS2 =
                createMotTest(testerOtherVTS, anotherSite, vehicle, 123456, TestOutcome.PASSED,
                        firstTestDate.plusMonths(1).plusDays(1).plusHours(1));
        String testNumber3 =
                createMotTest(tester1, defaultSite, vehicle, 123490, TestOutcome.FAILED,
                        firstTestDate.plusMonths(1).plusDays(1).plusHours(1).plusMinutes(1));

        //Login as DVSA and check order
        DuplicateReplacementCertificatePage duplicateReplacementCertificatePage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1, vehicle);

        assertThat(duplicateReplacementCertificatePage.getTestNumber(1), is(testNumber3));
        assertThat(duplicateReplacementCertificatePage.getTestNumber(2), is(testNumberOtherVTS2));
        assertThat(duplicateReplacementCertificatePage.getTestNumber(3), is(testNumber2));
        assertThat(duplicateReplacementCertificatePage.getTestNumber(4), is(testNumberOtherVTS1));
        assertThat(duplicateReplacementCertificatePage.getTestNumber(5), is(testNumber1));

        //Login as Tester and check order
        duplicateReplacementCertificatePage.clickLogout();
        duplicateReplacementCertificatePage = DuplicateReplacementCertificatePage
                .navigateHereFromLoginPage(driver, tester1, vehicle);

        assertThat(duplicateReplacementCertificatePage.getTestNumber(1), is(testNumber3));
        assertThat(duplicateReplacementCertificatePage.getTestNumber(2), is(testNumber2));
        assertThat(duplicateReplacementCertificatePage.getTestNumber(3), is(testNumber1));
    }

    @DataProvider(name = "reissueCertificateOnAnotherVTSByV5C")
    public Object[][] reissueCertificateOnAnotherVTSByV5C() {
        return new Object[][] {{"87652829", "3748202", TestOutcome.PASSED},
                {"6252840482", "281937362", TestOutcome.FAILED}};
    }

    @Test(groups = {"VM-4435", "VM-4416", "Regression", "W-Sprint2"},
            description = "Reissue a certificate using the V5C reference number.",
            dataProvider = "reissueCertificateOnAnotherVTSByV5C")
    public void testReissueCertificateOnAnotherVTSByV5C(String previousV5c, String v5c,
            TestOutcome testOutcome) {

        Site anotherSite = Site.JOHNS_GARAGE;
        Login tester = createTester(Arrays.asList(defaultSite.getId(), anotherSite.getId()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BOXSTER_2001);
        //Add V5C records to the vehicle
        addV5C(vehicle, previousV5c, new DateTime(), new DateTime(), null);
        addV5C(vehicle, v5c, null, null, null);
        //Create Mot Test
        createMotTest(tester, anotherSite, vehicle, 123456, testOutcome);

        DuplicateReplacementCertificatePage duplicateReplacementCertificatePage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, login, vehicle);

        if (testOutcome == TestOutcome.PASSED) {
            duplicateReplacementCertificatePage = duplicateReplacementCertificatePage
                    .enterFieldsOnFirstPassTestIssuedAtOtherVTSAndSubmitExpectingError(null,
                            previousV5c);

            assertThat(duplicateReplacementCertificatePage.getValidationMessage(),
                    is(Assertion.ASSERTION_DUPLICATES_TEST_NOT_FOUND.assertion));

            duplicateReplacementCertificatePage
                    .enterFieldsOnFirstPassTestIssuedAtOtherVTSAndSubmit(null, v5c);
        } else {
            duplicateReplacementCertificatePage = duplicateReplacementCertificatePage
                    .enterFieldsOnFirstFailTestIssuedAtOtherVTSAndSubmitExpectingError(null,
                            previousV5c);

            assertThat(duplicateReplacementCertificatePage.getValidationMessage(),
                    is(Assertion.ASSERTION_DUPLICATES_TEST_NOT_FOUND.assertion));

            duplicateReplacementCertificatePage
                    .enterFieldsOnFirstFailTestIssuedAtOtherVTSAndSubmit(null, v5c);
        }
    }

    @Test(groups = {"VM-4435", "Regression", "W-Sprint2"},
            description = "Check validation messages in Duplicate or replacement certificate page")
    public void testValidationMessagesWhenReissueCertificateOnAnotherVTS() {

        Site anotherSite = Site.JOHNS_GARAGE;
        Login tester = createTester(Arrays.asList(defaultSite.getId(), anotherSite.getId()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BOXSTER_2001);
        // Create Mot Test
        String motNumber = createMotTest(tester, anotherSite, vehicle, 123456, TestOutcome.PASSED);

        // Validation message when enter v5c and mot test number
        DuplicateReplacementCertificatePage duplicateReplacementCertificatePage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, login, vehicle);
        duplicateReplacementCertificatePage
                .enterFieldsOnFirstPassTestIssuedAtOtherVTSAndSubmitExpectingError(motNumber,
                        "87897907");
        Assert.assertEquals(duplicateReplacementCertificatePage.getValidationMessage(),
                Assertion.ASSERTION_DUPLICATES_MUST_ENTER_EITHER_V5C_OR_CERT_NUMBER.assertion);

        // Validation message when submit with no values
        duplicateReplacementCertificatePage
                .enterFieldsOnFirstPassTestIssuedAtOtherVTSAndSubmitExpectingError(null, null);

        assertThat((duplicateReplacementCertificatePage.getValidationMessage().isEmpty()),
                is(false));

        // Validation message when submit with incorrect v5c
        duplicateReplacementCertificatePage
                .enterFieldsOnFirstPassTestIssuedAtOtherVTSAndSubmitExpectingError(null,
                        "7876463270");
        assertThat((duplicateReplacementCertificatePage.getValidationMessage().isEmpty()),
                is(false));

        // Validation message when submit with incorrect mot number
        duplicateReplacementCertificatePage
                .enterFieldsOnFirstPassTestIssuedAtOtherVTSAndSubmitExpectingError("787908323270",
                        null);
        assertThat((duplicateReplacementCertificatePage.getValidationMessage().isEmpty()),
                is(false));
    }

    @Test(groups = {"VM-4450", "Regression", "W-Sprint3"})
    public void testIsAllowToDuplicateAbandonedCertificatesIssuedOnAnotherSite() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);
        Site anotherSite = Site.JOHNS_GARAGE;
        Login tester = createTester(Arrays.asList(defaultSite.getId(), anotherSite.getId()));
        //Create one passed and one failed mot test
        String passedMotNumber =
                createMotTest(tester, defaultSite, vehicle, 1346, TestOutcome.PASSED,
                        DateTime.now().minusDays(2));
        String failedMotNumber =
                createMotTest(tester, defaultSite, vehicle, 1358, TestOutcome.FAILED,
                        DateTime.now().minusDays(1));

        //Create Abandoned mot test on another site
        MotTestPage motTestPage = UserDashboardPage.navigateHereFromLoginPage(driver, tester)
                .startMotTestAsManyVtsTesterWithoutVtsChosen().selectAndConfirmVTS(anotherSite)
                .submitSearch(vehicle).startTest();
        motTestPage.clickCancelMotTest().enterAndSubmitReasonsToCancelPageExpectingAbandonedPage(
                ReasonToCancel.REASON_DANGEROUS_OR_CAUSE_DAMAGE, Text.TEXT_PASSCODE).clickLogout();

        DuplicateReplacementCertificatePage duplicateReplacementCertificatePage =
                UserDashboardPage.navigateHereFromLoginPage(driver, tester)
                        .reissueCertificateExpectingLocationSelectPage()
                        .selectAndConfirmVTSExpectingDuplicateReplacementCertificateSearchPage(
                                defaultSite)
                        .submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg);
        //Assertions
        assertThat("First not abandoned test must display 'Edit' button",
                duplicateReplacementCertificatePage
                        .isReplacementCertificateEditButtonDisplayed(failedMotNumber), is(true));
        assertThat("Only first not abandoned test should display 'Edit' button",
                duplicateReplacementCertificatePage
                        .isReplacementCertificateEditButtonDisplayed(passedMotNumber), is(false));
    }

    @DataProvider(name = "usersAllowedDuplicatesIssuedMoreThan18MonthsAgo")
    public Object[][] usersAllowedDuplicatesIssuedMoreThan18MonthsAgo() {
        return new Object[][] {{Login.LOGIN_CUSTOMER_SERVICE, false},
                {Login.LOGIN_DVLA_CENTRAL_OPERATIVE, true}, {Login.LOGIN_AREA_OFFICE1, true}};
    }

    @Test(groups = {"VM-4515", "Regression", "W-Sprint3"},
            dataProvider = "usersAllowedDuplicatesIssuedMoreThan18MonthsAgo")
    public void testUsersAllowedDuplicatesIssuedMoreThan18MonthsAgo(Login user,
            boolean shouldDuplicateMoreThan18Months) {

        Login login = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);
        String less18TestNumber =
                createMotTest(login, defaultSite, vehicle, 27836, TestOutcome.FAILED,
                        new DateTime().minus(Period.months(17)));
        String more18TestNumber =
                createMotTest(login, defaultSite, vehicle, 27870, TestOutcome.FAILED,
                        new DateTime().minus(Period.months(19)));

        DuplicateReplacementCertificatePage duplicateReplacementCertificatePage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, user, vehicle);

        assertThat(duplicateReplacementCertificatePage
                .isReplacementCertificateViewDisplayed(less18TestNumber), is(true));

        if (shouldDuplicateMoreThan18Months)

            assertThat(duplicateReplacementCertificatePage
                    .isReplacementCertificateViewDisplayed(more18TestNumber), is(true));
        else
            assertThat(duplicateReplacementCertificatePage
                    .isReplacementCertificateViewDisplayed(more18TestNumber), is(false));
    }

    @DataProvider(name = "usersAllowedIssueDuplicatesAndNotAllowedIssueReplacements")
    public Object[][] usersAllowedIssueDuplicatesAndNotAllowedIssueReplacements() {
        return new Object[][] {{Login.LOGIN_CUSTOMER_SERVICE},
                };
    }

    @Test(groups = {"VM-4511", "Regression", "W-Sprint4"},
            dataProvider = "usersAllowedIssueDuplicatesAndNotAllowedIssueReplacements",
            description = "Test roles that can't issue replacement Test Documents")
    public void testUsersNotAllowedIssueReplacement(Login user) {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);
        String testNumber = createMotTest(login, defaultSite, vehicle, 23486, TestOutcome.FAILED);
        DuplicateReplacementCertificatePage duplicateReplacementCertificatePage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, user, vehicle);

        assertThat(duplicateReplacementCertificatePage
                .isReplacementCertificateEditButtonDisplayed(testNumber), is(false));
    }

//    @Test(groups = {"VM-7785", "VM-10120", "Regression"})
    public void testManuallyEnterMakeAndModel() throws IOException {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);
        String testNumber = createMotTest(login, defaultSite, vehicle, 872033, TestOutcome.FAILED);
        Login dvsaUser = Login.LOGIN_AREA_OFFICE1;
        ReplacementCertificateUpdatePage replacementCertificateUpdatePage =
                ReplacementCertificateUpdatePage
                        .navigateHereFromLoginPage(driver, dvsaUser, vehicle, testNumber)
                        .enterVehicleMakeManually(VehicleMake.Other)
                        .submitOtherMakeAndModel("Kia", "C'eed");

        assertThat(replacementCertificateUpdatePage.getMakeText(), is("Kia"));
        assertThat(replacementCertificateUpdatePage.getModelText(), is("C'eed"));

        replacementCertificateUpdatePage.enterReasonForReplacement("None").reviewChangesButton();
        ReplacementCertificateReviewPage replacementCertificateReviewPage =
                new ReplacementCertificateReviewPage(driver);
        String customModel = replacementCertificateReviewPage.vehicleModel();
        String customMake = replacementCertificateReviewPage.vehicleMake();

        String motTestNumber = replacementCertificateReviewPage.getMotTestNumber();
        ReplacementCertificateCompletePage replacementCertificateCompletePage =
                replacementCertificateReviewPage.clickPrintButton();

        String fileName = replacementCertificateReviewPage.generateNewVT30FileName();
        String pathNFileName = getErrorScreenshotPath() + "/" + fileName;
        Utilities.copyUrlBytesToFile(replacementCertificateCompletePage.getPrintCertificateUrl(),
                driver, pathNFileName);
        replacementCertificateCompletePage.clickDoneButton();
        String parsedText = Utilities.pdfToText(pathNFileName);
        VerifyCertificateDetails ver = new VerifyCertificateDetails();

        assertThat("Verify VT30 title", ver.getTitle(parsedText), is(Text.TEXT_VT30_TITLE));
        assertThat("Verify motTestNumber",
                ver.getVT20TestNumberMakeModel(parsedText).contains(motTestNumber), is(true));
        assertThat("Verify Custom Make of Vehicle on Certificate",
                ver.getVehicleMakeAndModelDetailsFromCertificate(parsedText).contains(customMake),
                is(true));
        assertThat("Verify Custom Model of Vehicle on Certificate",
                ver.getVehicleMakeAndModelDetailsFromCertificate(parsedText).contains(customModel),
                is(true));

    }

    @DataProvider(name = "usersNotAllowedToSearch") public Object[][] usersNotAllowedToSearch() {

        return new Object[][] {{createVE()}, {Login.LOGIN_AED1}, {Login.LOGIN_AEDM},
                {Login.LOGIN_SITE_MANAGER}, {Login.LOGIN_SITE_ADMIN_AT_VTS1}};
    }

    @Test(groups = "Regression", dataProvider = "usersNotAllowedToSearch")
    public void testReplacementDuplicateSearchLinkNotDisplayed(Login login) {

        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);
        assertThat("validate that replacement/duplicate link is not displayed",
                userDashboardPage.isCertificateReissueLinkDisplayed(), is(false));
    }
}
