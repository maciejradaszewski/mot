package com.dvsa.mot.selenium.priv.frontend.enforcement.tests;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.enums.VehicleDetails;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.Utilities;
import com.dvsa.mot.selenium.framework.api.MotTestApi;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.*;
import org.joda.time.DateTime;
import org.testng.Assert;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import java.util.Collections;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class EnforcementAdvancedSearchTests extends BaseTest {

    AeService aeService = new AeService();
    AeDetails aeDetails = aeService.createAe("NEWAeCREATED");

    @Test(groups = {"VM-1800", "VM-1801", "VM-1802", "VM-1803", "Sprint17", "Sprint18", "Sprint19",
            "Enf", "Regression"}) public void searchForMOTTestsBy() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BOXSTER_2001);
        createMotTest(login, Site.POPULAR_GARAGES, vehicle, 12000, MotTestApi.TestOutcome.FAILED);

        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER);
        homePage.clickMOTLink();
        verifyMOTTestsByDateValidation();
        searchWithDifferentVINs(vehicle);
        searchWithDifferentVRMs(vehicle);
        searchVTSByDateRanges();
        searchVTSByRecentTests();
    }

    public void searchWithDifferentVINs(Vehicle vehicle) {

        EnforcementVTSSearchPage enforcementVTSSearchPage = new EnforcementVTSSearchPage(driver);
        enforcementVTSSearchPage.selectDropdown(Text.TEXT_ENF_VIN_SEARCH).enterSearchCriteria("")
                .clickSearch();

        assertThat(ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

        enforcementVTSSearchPage.enterSearchCriteria(Vehicle.VEHICLE_CLASS4_NON_EXISTENT.fullVIN)
                .clickSearch();

        assertThat(ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

        enforcementVTSSearchPage.enterSearchCriteria(vehicle.fullVIN).clickSearch();
        EnforcementVTSSearchHistoryPage enforcementVTSSearchHistoryPage =
                new EnforcementVTSSearchHistoryPage(driver);
        Assert.assertTrue(enforcementVTSSearchHistoryPage.isResultsTableDisplayed(),
                "Table Data is not Displayed");
        enforcementVTSSearchHistoryPage.clickOnGoBackLink();
    }

    public void searchWithDifferentVRMs(Vehicle vehicle) {

        EnforcementVTSSearchPage enforcementVTSSearchPage = new EnforcementVTSSearchPage(driver);
        enforcementVTSSearchPage.selectDropdown(Text.TEXT_ENF_REGISTRATION_SEARCH)
                .enterSearchCriteria("").clickSearch();


        assertThat(ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

        enforcementVTSSearchPage.enterSearchCriteria(Vehicle.VEHICLE_CLASS4_NON_EXISTENT_2.carReg)
                .clickSearch();


        assertThat(ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

        enforcementVTSSearchPage.enterSearchCriteria(vehicle.carReg).clickSearch();
        EnforcementVTSSearchHistoryPage enforcementVTSSearchHistoryPage =
                new EnforcementVTSSearchHistoryPage(driver);
        Assert.assertTrue(enforcementVTSSearchHistoryPage.isResultsTableDisplayed(),
                "Table Data is not Displayed");
        enforcementVTSSearchHistoryPage.clickOnGoBackLink();
    }

    public void searchVTSByDateRanges() {

        EnforcementVTSSearchPage enforcementVTSSearchPage = new EnforcementVTSSearchPage(driver);
        enforcementVTSSearchPage.selectDropdown(Text.TEXT_ENF_SITE_DATE_RANGE_SEARCH)
                .vtsByDateRangeNull();


        assertThat(ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

        enforcementVTSSearchPage.vtsByDateRangeInvalid();

        assertThat(ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

        enforcementVTSSearchPage.vtsByValidDateRange().enterSearchCriteria("V1234").clickSearch();
        EnforcementVTSSearchHistoryPage enforcementVTSSearchHistoryPage =
                new EnforcementVTSSearchHistoryPage(driver);
        Assert.assertTrue(enforcementVTSSearchHistoryPage.isResultsTableDisplayed(),
                "Table Data is not Displayed");
        enforcementVTSSearchHistoryPage.clickOnSearchAgain();
    }

    public void searchVTSByRecentTests() {

        EnforcementVTSSearchPage enforcementVTSSearchPage = new EnforcementVTSSearchPage(driver);
        enforcementVTSSearchPage.selectDropdown(Text.TEXT_ENF_SITE_SEARCH).enterSearchCriteria("")
                .clickSearch();

        assertThat(ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

        enforcementVTSSearchPage.enterSearchCriteria("V9090").clickSearch();

        assertThat(ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

        enforcementVTSSearchPage.enterSearchCriteria("V1234").clickSearch();
        EnforcementVTSSearchHistoryPage enforcementVTSSearchHistoryPage =
                new EnforcementVTSSearchHistoryPage(driver);
        Assert.assertTrue(enforcementVTSSearchHistoryPage.isResultsTableDisplayed(),
                "Table Data is not Displayed");
    }

    public void verifyMOTTestsByDateValidation() {
        EnforcementVTSSearchPage enforcementVTSSearchPage = new EnforcementVTSSearchPage(driver);
        enforcementVTSSearchPage.selectDropdown(Text.TEXT_ENF_TESTER_DATE_RANGE_SEARCH)
                .clearFieldStartMonth().enterStartMonth("00").clickSearch();
        Assert.assertEquals(enforcementVTSSearchPage.getValidationTextValue(),
                Text.TEXT_ENF_MOT_SEARCH_INVALID_MONTH);

        enforcementVTSSearchPage.clearFieldStartYear().enterStartYear("0000").clickSearch();
        Assert.assertEquals(enforcementVTSSearchPage.getValidationTextValue(),
                Text.TEXT_ENF_MOT_SEARCH_INVALID_ONE_MONTH_AND_A_YEAR);

        enforcementVTSSearchPage.clearFieldEndMonth().enterEndMonth("00").clickSearch();
        Assert.assertEquals(enforcementVTSSearchPage.getValidationTextValue(),
                Text.TEXT_ENF_MOT_SEARCH_INVALID_FOR_TWO_MONTHS_AND_A_YEAR);

        enforcementVTSSearchPage.clearFieldEndYear().enterEndYear("0000").clickSearch();
        Assert.assertEquals(enforcementVTSSearchPage.getValidationTextValue(),
                Text.TEXT_ENF_MOT_SEARCH_INVALID_FOR_TWO_MONTHS_AND_TWO_YEARS);
    }

    @Test(groups = {"VM-1803", "Sprint19", "Enf", "Test08", "Regression"})
    public void openSummaryLink() {
        //given
        MOTSearchDetails historySearchDetails = prepareTestHistory(getTestClassName());
        Login vehicleExaminer = createVE();

        //when
        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, vehicleExaminer);
        homePage.clickMOTLink();
        EnforcementVTSSearchPage searchVTSPage = new EnforcementVTSSearchPage(driver);
        searchVTSPage.selectDropdown(Text.TEXT_ENF_TESTER_DATE_RANGE_SEARCH);
        searchVTSPage.vtsByValidDateRange();
        searchVTSPage.enterSearchCriteria(historySearchDetails.tester);
        EnforcementVTSSearchHistoryPage historyPage = searchVTSPage.clickSearch();
        historyPage.clickSummaryLink();
        EnforcementMotTestSummaryPage summaryPage = new EnforcementMotTestSummaryPage(driver);

        //then
        Assert.assertTrue(summaryPage.checkEnforcementSummaryPageDisplay(),
                "no results are  displayed");
    }

    private MOTSearchDetails prepareTestHistory(String diff) {
        int aeId = createAE("AE_" + diff);
        String siteName = "VTS_" + diff;
        Site site = new VtsCreationApi()
                .createVtsSite(aeId, TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, siteName);
        Login tester = createTester(Collections.singleton(site.getId()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BOXSTER_2001);

        DateTime issuedDate = DateTime.now().minusDays(10);
        createMotTest(tester, site, vehicle, 14000, MotTestApi.TestOutcome.PASSED, issuedDate);

        return new MOTSearchDetails(site.getNumber(), tester.username, vehicle.carReg,
                vehicle.fullVIN);

    }

    @Test(groups = {"VM-2799", "VM-3154", "Sprint24", "Enf", "Regression"})
    public void verifyUserRoles() {
        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER);
        homePage.clickMyProfile();

        // If the DB Table 'organisation_business_role' table's 'name' column is updated this test should be updated.
        Assert.assertTrue(homePage.getDisplayRole().contains("Vehicle examiner"));

        Assert.assertFalse(homePage.getDisplayRole().contains("Tester"));
    }

    @Test(groups = {"VM-3734", "Sprint26", "Enf"}) public void verifyAESearchResultsForVE() {

        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER);
        homePage.clickListAllAEs();

        SearchForAePage searchResultsPage = new SearchForAePage(driver);

        searchResultsPage.submitSearchForAe(aeDetails.getAeRef());

        AuthorisedExaminerFullDetailsPage authorisedExaminerFullDetailsPage =
                new AuthorisedExaminerFullDetailsPage(driver);
        Assert.assertEquals(authorisedExaminerFullDetailsPage.getAeNameDetails(),
                "Full Details of Authorised Examiner\n" + aeDetails.getAeName(),
                " Verify AE Details ");
    }

    @Test(groups = {"VM-2378", "Sprint27", "Enf", "Regression"}) public void verifyAEDetailsForVE() {

        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER);
        homePage.clickListAllAEs().submitSearchForAe(aeDetails.getAeRef())
                .verifyAePageElementsDVSAUsers();

        AuthorisedExaminerFullDetailsPage authorisedExaminerFullDetailsPage =
                new AuthorisedExaminerFullDetailsPage(driver);
        authorisedExaminerFullDetailsPage.clickSearchAgain();
    }

    @Test(groups = {"VM-3734", "Sprint26", "Enf", "Regression"})
    public void verifyAESearchResultsForDVSAAdmin() {

        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1);
        homePage.clickListAllAEs().submitSearchForAe(aeDetails.getAeRef());


    }

    @Test(groups = {"VM-1798", "Sprint1-V", "VM-4384", "Sprint2a-V", "Regression"},
            description = "Verify error message is displayed when user has entered any invalid search criteria")
    public void verifyInvalidVehicleSearch() {
        SearchVehicleInformationPage searchPage = SearchVehicleInformationPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER).verifyPageTitle()
                .selectVehicleType(Text.TEXT_VRM_TYPE).enterSearchTerm("").clickInvalidSearch();
        Assert.assertEquals(searchPage.getValidationErrors(),
                Assertion.ASSERTION_VEHICLE_SEARCH_NULL.assertion,
                "Assert the error message is displayed when search term was not entered");

        searchPage.enterSearchTerm(Vehicle.VEHICLE_CLASS4_NON_EXISTENT_3.carReg)
                .clickInvalidSearch();
        Assert.assertEquals(searchPage.getValidationErrors(),
                Assertion.ASSERTION_INVALID_VEHICLE_SEARCH.assertion,
                "Assert the error message is displayed when invalid search term was entered");

        searchPage.selectVehicleType(Text.TEXT_VIN_TYPE).clearSearchText().enterSearchTerm("")
                .clickInvalidSearch();
        Assert.assertEquals(searchPage.getValidationErrors(),
                Assertion.ASSERTION_MULTIPLE_INVALID_MESSAGES.assertion,
                "Assert the error message is displayed when search term was not entered");

        searchPage.enterSearchTerm(Vehicle.VEHICLE_CLASS4_NON_EXISTENT_3.fullVIN)
                .clickInvalidSearch();
        Assert.assertEquals(searchPage.getValidationErrors(),
                Assertion.ASSERTION_INVALID_VEHICLE_SEARCH.assertion,
                "Assert the error message is displayed when invalid search term was entered");
    }

    @DataProvider(name = "MultipleVRMSearch") public Object[][] vrmList() {
        return new Object[][] {

                {Login.LOGIN_ENFTESTER, Text.TEXT_VRM_TYPE,
                        Vehicle.VEHICLE_MULTIPLE_VALID_VRM.carReg,
                        Vehicle.VEHICLE_MULTIPLE_VALID_VRM, VehicleDetails.BMW_Mini},
                {Login.LOGIN_AREA_OFFICE1, Text.TEXT_VRM_TYPE,
                        Vehicle.VEHICLE_MULTIPLE_VALID_VRM.carReg,
                        Vehicle.VEHICLE_MULTIPLE_VALID_VRM, VehicleDetails.BMW_Mini}

        };
    }

    @Test(groups = {"VM-4183", "Sprint2a-V", "Regression"}, dataProvider = "MultipleVRMSearch",
            description = "Verify list of vehicles page was displayed when multiple possible matches found for VRM")
    public void verifyMultipleVRMs(Login login, String type, String searchTerm,
            Vehicle searchVehicle, VehicleDetails vehicleDetails) {
        VehicleDetailsPage detailsPage =
                SearchVehicleInformationPage.navigateHereFromLoginPage(driver, login)
                        .verifyPageTitle().selectVehicleType(type).enterSearchTerm(searchTerm)
                        .clickMultipleSearch().verifyPageTitle().verifyVrmsFoundTitle(searchTerm)
                        .verifyVrmsTable(searchTerm).clickDetailsLink();

        verifyVehicleDetailsPage(detailsPage, searchVehicle, vehicleDetails).clickGoBackLink()
                .verifyPageTitle().clickGoBackLink().verifyPageTitle();
    }

    @DataProvider(name = "MultipleVINSearch") public Object[][] vinList() {
        return new Object[][] {

                {Login.LOGIN_ENFTESTER, Text.TEXT_VIN_TYPE,
                        Vehicle.VEHICLE_MULTIPLE_VALID_VIN.fullVIN,
                        Vehicle.VEHICLE_MULTIPLE_VALID_VIN, VehicleDetails.Mercedes_C300},
                {Login.LOGIN_AREA_OFFICE1, Text.TEXT_VIN_TYPE,
                        Vehicle.VEHICLE_MULTIPLE_VALID_VIN.fullVIN,
                        Vehicle.VEHICLE_MULTIPLE_VALID_VIN, VehicleDetails.Mercedes_C300}};
    }

    @Test(groups = {"VM-4183", "Sprint2a-V", "Regression"}, dataProvider = "MultipleVINSearch",
            description = "Verify list of vehicles page was displayed when multiple possible matches found for VIN")
    public void verifyMultipleVINs(Login login, String type, String searchTerm,
            Vehicle searchVehicle, VehicleDetails vehicleDetails) {
        VehicleDetailsPage detailsPage =
                SearchVehicleInformationPage.navigateHereFromLoginPage(driver, login)
                        .verifyPageTitle().selectVehicleType(type).enterSearchTerm(searchTerm)
                        .clickMultipleSearch().verifyPageTitle().verifyVinsFoundTitle(searchTerm)
                        .verifyVinsTable(searchTerm).filterBy(searchVehicle.carReg)
                        .clickDetailsLink();

        verifyVehicleDetailsPage(detailsPage, searchVehicle, vehicleDetails).clickGoBackLink()
                .verifyPageTitle().clickGoBackLink().verifyPageTitle();
    }

    @DataProvider(name = "SingleVehicleSearch") public Object[][] singleVrmList() {
        return new Object[][] {{Login.LOGIN_ENFTESTER, Text.TEXT_VRM_TYPE,
                Vehicle.VEHICLE_CLASS1_KAWASAKI_2013.carReg, Vehicle.VEHICLE_CLASS1_KAWASAKI_2013,
                VehicleDetails.Kawasaki_ZX1400}, {Login.LOGIN_ENFTESTER, Text.TEXT_VIN_TYPE,
                Vehicle.VEHICLE_CLASS1_DAKOTA_1924.fullVIN, Vehicle.VEHICLE_CLASS1_DAKOTA_1924,
                VehicleDetails.VEHICLE_CLASS1_DAKOTA_1924},

        };
    }

    private VehicleDetailsPage verifyVehicleDetailsPage(VehicleDetailsPage detailsPage,
            Vehicle searchVehicle, VehicleDetails vehicleDetails) {
        detailsPage.verifyPageTitle().verifyPageElements().verifyRegNumber(searchVehicle.carReg)
                .verifyVin(searchVehicle.fullVIN).verifyMake(searchVehicle.make.getVehicleMake())
                .verifyModel(searchVehicle.model.getModelName())
                .verifyModelType(vehicleDetails.getModelType())
                .verifyFuelType(searchVehicle.fuelType.toString())
                .verifyCylinderCapacity(searchVehicle.cylinderCapacity)
                .verifyColor(searchVehicle.primaryColour.getColourName(),
                        searchVehicle.secondaryColour.getColourName()).
                verifyDetailsCreated().verifyDeclaredNew(vehicleDetails.getDeclaredNew())
                .verifyManufactureDate(vehicleDetails.getDateOfMake())
                .verifyNoOfSeats(vehicleDetails.getNoSeats())
                .verifyRegistrationDate(vehicleDetails.getDateOfReg())
                .verifyNoOfSeatBelts(vehicleDetails.getNoSeatBelts())
                .verifyFirstUseDate(Utilities.convertDateToGDSFormat(searchVehicle.dateOfFirstUse))
                .verifySeatBeltLastCheckedDate(vehicleDetails.getDateOfSeatBelt());

        return new VehicleDetailsPage(driver);
    }
}
