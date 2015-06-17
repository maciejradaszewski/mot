package com.dvsa.mot.selenium.priv.frontend.helpdesk;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.AuthorisedExaminerFullDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EventHistoryPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.openam.OpenAMClaimAccountSignInPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.apache.commons.lang3.RandomStringUtils;
import org.joda.time.LocalDate;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import java.util.ArrayList;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;
import static org.testng.Assert.*;

public class HelpDeskTests extends BaseTest {

    private AeService aeService = new AeService();

    @Test(groups = {"VM-4741", "slice_A",
            "W-Sprint5"}, description = "Verify error message was displayed when search for blank field values in the helpdesk user search page")
    public void testHelpdeskUserSearchPageValidations() {
        HelpdeskUserSearchPage helpdeskUserSearchPage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .searchExpectigError();

        assertEquals(helpdeskUserSearchPage.getValidationSummary(),
                Assertion.ASSERTION_HELPDESK_ERROR_INPUT_REQUIRED.assertion);
    }

    @Test(groups = {"VM-4741", "slice_A",
            "W-Sprint5"}, description = "Verify error message was displayed when search for invalid date of birth in the helpdesk user search page")
    public void testHelpdeskUserSearchPageInvalidDateOfBirth() {
        HelpdeskUserSearchPage helpdeskUserSearchPage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterDayOfBirth("31").enterMonthOfBirth("02").enterYearOfBirth("1980")
                .searchExpectigError();

        assertEquals(helpdeskUserSearchPage.getValidationSummary(),
                Assertion.ASSERTION_HELPDESK_ERROR_INVALID_DATE.assertion);
    }

    @Test(groups = {"VM-4741", "slice_A",
            "W-Sprint5"}, description = "Verify error message was displayed when search for invalid format date of birth in the helpdesk user search page")
    public void testHelpdeskUserSearchPageInvalidFormatDateOfBirth() {
        HelpdeskUserSearchPage helpdeskUserSearchPage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterDayOfBirth("1").enterMonthOfBirth("1").enterYearOfBirth("12")
                .searchExpectigError();

        assertEquals(helpdeskUserSearchPage.getValidationSummary(),
                Assertion.ASSERTION_HELPDESK_ERROR_INCORRECT_DATE_FORMAT.assertion);
    }

    @Test(groups = {"VM-4741", "slice_A",
            "W-Sprint5"}, description = "Verify proper message was displayed when helpdesk user search page return too many results")
    public void testHelpdeskUserSearchTooManyResults() {
        Person tester = Person.BOB_THOMAS;
        HelpdeskUserSearchPage helpdeskUserSearchPage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterFirstName(tester.getName()).searchExpectigError();

        assertTrue(helpdeskUserSearchPage.getInfoMessage()
                .contains(Assertion.ASSERTION_HELPDESK_ERROR_TOO_MANY_RESULTS.assertion));
        assertEquals(helpdeskUserSearchPage.getFirstName(), tester.getName());
    }

    @Test(groups = {"VM-4741", "slice_A",
            "W-Sprint5"}, description = "Verify proper message was displayed when helpdesk user search page return no results")
    public void testHelpdeskUserSearchNoResults() {
        String username = "qpljkjhjhjk";
        Person tester = Person.BOB_THOMAS;
        LocalDate dateOfBirth = new LocalDate(tester.year, 1, tester.day);

        HelpdeskUserSearchPage helpdeskUserSearchPage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterUsername(username).enterFirstName(tester.getName())
                .enterLastName(tester.getSurname()).enterDateOfBirth(dateOfBirth)
                .enterPostcode(tester.address.getPostcode()).searchExpectigError();

        assertTrue(helpdeskUserSearchPage.getInfoMessage()
                .contains(Assertion.ASSERTION_HELPDESK_ERROR_NO_RESULTS.assertion));
        assertEquals(helpdeskUserSearchPage.getUsername(), username);
        assertEquals(helpdeskUserSearchPage.getFirstName(), tester.getName());
        assertEquals(helpdeskUserSearchPage.getDayOfBirth(), dateOfBirth.toString("dd"));
        assertEquals(helpdeskUserSearchPage.getMonthOfBirth(), dateOfBirth.toString("MM"));
        assertEquals(helpdeskUserSearchPage.getYearOfBirth(), dateOfBirth.toString("yyyy"));
        assertEquals(helpdeskUserSearchPage.getPostcode(), tester.address.getPostcode());
    }

    @Test(groups = {"VM-4698", "VM-4842", "VM-7724", "V-Sprint10", "slice_A",
            "W-Sprint4"}, description = "Verify profile page data and go back to search results")
    public void testHelpdeskSearchUserByUsernameCheckProfileAndGoBack() {
        Person tester = Person.BOB_THOMAS;
        HelpdeskUserResultsPage helpdeskUserResultsPage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterUsername(Login.LOGIN_TESTER1.username).search();

        assertEquals(helpdeskUserResultsPage.getNumResults(), 1,
                "Check the number of results returned");
        assertEquals(helpdeskUserResultsPage.getResultName(0), tester.getNamesAndSurname(),
                "Check names and surname");
        assertEquals(helpdeskUserResultsPage.getResultDateOfBirth(0), tester.getDateOfBirth(),
                "Check the date of birth");
        assertEquals(helpdeskUserResultsPage.getResultAddress(0), tester.address.getShortAddress(),
                "Check address");
        assertEquals(helpdeskUserResultsPage.getResultPostcode(0), tester.address.getPostcode(),
                "Check postcode");

        HelpdeskUserSearchPage helpdeskUserSearchPage = helpdeskUserResultsPage.backToUserSearch();

        assertTrue(helpdeskUserSearchPage.isSearchButtonDisplayed(),
                "Check if the search button is displayed");

    }

    @Test(groups = {"VM-4698", "VM-4842", "VM-7724", "V-Sprint10", "slice_A", "slice_D",
            "W-Sprint4"}, description = "Verify user profile field values")
    public void testHelpdeskSearchUserByAllFieldsAndCheckProfile() {
        Person tester = Person.BOB_THOMAS;
        HelpdeskUserResultsPage helpdeskUserResultsPage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterUsername(tester.login.username).enterFirstName(tester.getName())
                .enterLastName(tester.getSurname()).enterDayOfBirth(Integer.toString(tester.day))
                .enterMonthOfBirth("04").enterYearOfBirth(Integer.toString(tester.year))
                .enterPostcode(tester.address.getPostcode()).search();

        assertEquals(helpdeskUserResultsPage.getResultName(0), tester.getNamesAndSurname());
        assertEquals(helpdeskUserResultsPage.getResultDateOfBirth(0), tester.getDateOfBirth());
        assertEquals(helpdeskUserResultsPage.getResultAddress(0), tester.address.getShortAddress());
        assertEquals(helpdeskUserResultsPage.getResultPostcode(0), tester.address.getPostcode());
    }

    @Test(groups = {"VM-4880", "VM-4881", "VM-4842", "VM-7724", "V-Sprint10", "W-Sprint5",
            "slice_A", "slice_D"}, description = "Verify ResetPassword functionality")
    public void testHelpdeskResetPasswordSuccessfully() {

        ArrayList vts = new ArrayList();
        AeDetails aeDetails = aeService.createAe("Test_AE1");
        int vtsId =
                createVTS(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, "Test_VTS1");
        vts.add(vtsId);
        Person person = createTesterAsPerson(vts);
        Login user = person.getLogin();
        HelpdeskResetPasswordSuccessPage helpdeskResetPasswordSuccessPage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterUsername(user.username).search().clickUserName(0).clickResetPassword();

        assertEquals(helpdeskResetPasswordSuccessPage.getFirstTimeResetPasswordConfirmationText(),
                "A letter will be sent to " + person.getNamesAndSurname()
                        + " giving instructions on how to reset the password for their account.",
                "Check the correct message is displayed when reset password is triggered by post");
    }


    @Test(groups = {"VM-4880", "VM-4881", "VM-4842", "VM-7724", "V-Sprint10", "W-Sprint5",
            "slice_A", "slice_D"}, description = "Verify RecoverUserName functionality")
    public void testHelpdeskResetUsernameSuccessfully() {

        ArrayList vts = new ArrayList();
        AeDetails aeDetails = aeService.createAe("Test_AE");
        int vtsId =
                createVTS(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, "Test_VTS");
        vts.add(vtsId);
        Person person = createTesterAsPerson(vts);
        Login user = person.getLogin();
        HelpdeskRecoverUsernameSuccessPage helpdeskRecoverUsernameSuccessPage =
                HelpdeskUserSearchPage
                        .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                        .enterUsername(user.username).search().clickUserName(0)
                        .clickRecoverUsername();

        assertEquals(helpdeskRecoverUsernameSuccessPage.getSuccessfulResetUsernameMessage(),
                "A letter will be sent to " + person.getNamesAndSurname()
                        + " containing the username for their MOT account.",
                "Check the correct message is displayed when username recovery is triggered by post");
    }

    @Test(groups = {"VM-4880", "VM-4842", "VM-7724", "V-Sprint10", "W-Sprint5", "slice_A",
            "slice_D"}, description = "Verify ReturnToHome functionality in the user profile page")
    public void testGoToUserProfilePageAndGoToHome() {
        Login user = createTester(true);
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver).navigateToClaimAccountPage(driver, user);
        openAMClaimAccountSignInPage.submitEmailSuccessfully(
                Person.getUnique(Person.PERSON_1, "loginIntoOpenAMClaimAccount")).enterNewPassword(Text.TEXT_RESET_PASSWORD)
                .enterNewConfirmPassword(Text.TEXT_RESET_PASSWORD).clickOnSubmitButton()
                .setSecurityQuestionAndAnswersSuccessfully().clickOnSubmitButton()
                .clickSaveAndContinue().clickLogout();
        UserDashboardPage userDashboardPage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterUsername(user.username).search().clickUserName(0).returnToHome();

        assertTrue(userDashboardPage.isHelpdeskSearchUserLinkClickable(),
                "Check to ensure CSCO is navigated back to home page");
    }

    @Test(groups = {"VM-4880", "W-Sprint5", "VM-4842", "VM-7724", "V-Sprint10", "slice_A",
            "slice_D"}, description = "Verify BackToSearchResults functionality in the user profile page")
    public void testGoToUserProfilePageAndBackToSearchResults() {
        Login user = createTester(true);
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver).navigateToClaimAccountPage(driver, user);
        openAMClaimAccountSignInPage.submitEmailSuccessfully(
                Person.getUnique(Person.PERSON_1, "loginIntoOpenAMClaimAccount")).enterNewPassword(Text.TEXT_RESET_PASSWORD)
                .enterNewConfirmPassword(Text.TEXT_RESET_PASSWORD).clickOnSubmitButton()
                .setSecurityQuestionAndAnswersSuccessfully().clickOnSubmitButton()
                .clickSaveAndContinue().clickLogout();
        HelpdeskUserResultsPage helpdeskUserResultsPage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterUsername(user.username).search().clickUserName(0).backToSearchResults();

        assertEquals(helpdeskUserResultsPage.getNumResults(), 1,
                "Check number of users returned from user search");
    }

    @DataProvider(name = "usersNotAllowedHelpdeskUserProfile")
    public Object[][] usersNotAllowedHelpdeskUserProfile() {
        return new Object[][] {{Login.LOGIN_TESTER1}, {Login.LOGIN_AEDM},
                {Login.LOGIN_SCHEME_MANAGEMENT}, {Login.LOGIN_AREA_OFFICE1}};
    }

    @DataProvider(name = "dvsaUserCanSearchForAUser")
    public Object[][] dvsaUserCanSearchForAUser() {
        return new Object[][] {{Login.LOGIN_AREA_OFFICE1}, {Login.LOGIN_ENFTESTER},
                {Login.LOGIN_ENFTESTER4}};
    }

    @Test(groups = {"VM-7646", "slice_A"}, dataProvider = "dvsaUserCanSearchForAUser")
    public void testCanDvsaUserConductUserSearch(Login login) {
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);

        assertTrue(userDashboardPage.isHelpdeskSearchUserLinkClickable(),
                "user search link is present and clickable");
    }

    @Test(groups = {"VM-7646", "slice_A"}, dataProvider = "dvsaUserCanSearchForAUser")
    public void testDvsaUserCanViewSearchResults(Login login) {
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);
        userDashboardPage.clickUserSearch().enterUsername("tester1").search();
        HelpdeskUserResultsPage helpdeskUserResultsPage = new HelpdeskUserResultsPage(driver);

        assertTrue(helpdeskUserResultsPage.checkSearchResultsTable("Name"),
                "Name column is present");
        assertTrue(helpdeskUserResultsPage.checkSearchResultsTable("Username"),
                "Username column is present");
        assertTrue(helpdeskUserResultsPage.checkSearchResultsTable("Date of birth"),
                "Date of birth column is present");
        assertTrue(helpdeskUserResultsPage.checkSearchResultsTable("Address"),
                "Address column is present");
        assertTrue(helpdeskUserResultsPage.checkSearchResultsTable("Postcode"),
                "Postcode column is present");
    }

    @Test(groups = {"VM-7646", "slice_A"}, dataProvider = "dvsaUserCanSearchForAUser")
    public void testDvsaUserCanSearchOnTown(Login login) {
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);
        userDashboardPage.clickUserSearch().enterTown(Text.TEXT_ENTER_TOWN).search();
        HelpdeskUserResultsPage helpdeskUserResultsPage = new HelpdeskUserResultsPage(driver);

        assertTrue(helpdeskUserResultsPage
                .checkSearchResultsTable(Assertion.ASSERTION_USER_ADDRESS.assertion));
    }

    @Test(groups = {"VM-7295", "slice_A"}, dataProvider = "dvsaUserCanSearchForAUser")
    public void testUserCanClickOnEventsHistoryLink(Login login) {
        EventHistoryPage eventHistoryPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login).clickUserSearch()
                        .enterUsername("tester1").search().clickUserName(0).clickEventHistoryLink();

        assertTrue(eventHistoryPage.checkTableExists(),
                "Check if event history table page is displayed");
    }


    @Test(groups = {"VM-8049", "slice_A"}, dataProvider = "dvsaUserCanSearchForAUser")
    public void testDvsaUserCanViewVtsAssociationSuccessfully(Login login) {
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);
        userDashboardPage.clickUserSearch().enterUsername("tester1").search().clickUserName(0)
                .getVehicleTestingStationPage();
        SiteDetailsPage siteDetailsPage = new SiteDetailsPage(driver);

        assertThat("Vehicle Testing Station page is displayed",
                siteDetailsPage.checkChangeOpeningHoursLinkExists(), is(true));
    }

    @Test(groups = {"VM-8049", "slice_A"}, dataProvider = "dvsaUserCanSearchForAUser")
    public void testDvsaUserCanViewAeAssociationSuccessfully(Login login) {
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);
        userDashboardPage.clickUserSearch().enterUsername("ae").search().clickUserName(0)
                .getAuthorisedExaminerPage();
        AuthorisedExaminerFullDetailsPage
                authorisedExaminerFullDetailsPage = new AuthorisedExaminerFullDetailsPage(driver);

        assertThat("Full Details of Authorised Examiner page is displayed",
                authorisedExaminerFullDetailsPage.checkSearchAgainLinkExists(), is(true));
    }

    @Test(groups = {"VM-8049", "slice_A"})
    public void testCscoUserCanViewVtsAssociationSuccessfully() {
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE);
        userDashboardPage.clickUserSearch().enterUsername("tester1").search().clickUserName(0);
        SiteDetailsPage siteDetailsPage =
                new HelpDeskUserProfilePage(driver).getVehicleTestingStationPage();

        assertThat("Vehicle Testing Station page is displayed without change opening hours link",
                siteDetailsPage.checkChangeOpeningHoursLinkExists(), is(false));
    }

    @Test(groups = {"VM-9084", "slice_A"})
    public void testCSCOCanSearchForUsersWithUnclaimedAccounts() {
        Login unclaimedAccountUser = createTester(true);
        HelpDeskUserProfilePage helpDeskUserProfilePage =
                UserDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                        .clickUserSearch().enterUsername(unclaimedAccountUser.username).search()
                        .clickUserName(0);

        assertEquals(helpDeskUserProfilePage.getUserName(), unclaimedAccountUser.username,
                "Check that username searched is displayed on profile page");
    }

    @Test(groups = {"VM-9085", "slice_A"}) public void testCSCOCanReclaimAccountByPost() {

        ArrayList vts = new ArrayList();
        String aeName = RandomStringUtils.randomAlphabetic(6);
        String vtsName = RandomStringUtils.randomAlphabetic(6);
        AeDetails aeDetails = aeService.createAe(aeName);
        int vtsId = createVTS(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        vts.add(vtsId);
        Person person = createTesterAsPerson(vts);
        Login user = person.getLogin();


        HelpDeskResetAccountConfirmationPage helpDeskResetAccountConfirmationPage =
                HelpdeskUserSearchPage
                        .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                        .enterUsername(user.username).search().clickUserName(0)
                        .resetAccountByPost();

        assertEquals(helpDeskResetAccountConfirmationPage.getAccountReclaimConfirmationMessage(),
                "You should only start this process if " + person.getFullName()
                        + " has forgotten their security questions and password.",
                "Check confirmation message");
        assertEquals(helpDeskResetAccountConfirmationPage.getAccountReclaimNotificationMessage(),
                "This will reset the user's password and require them to set up their security questions and PIN when they next sign in.",
                "Check notification message");

        HelpDeskUserProfilePage helpDeskUserProfilePage =
                helpDeskResetAccountConfirmationPage.clickReclaimAccountButton();

        assertTrue(helpDeskUserProfilePage.isAccountReclaimASuccess(),
                "Check if account reclaim was a success");
        assertEquals(helpDeskUserProfilePage.getAccountReclaimByPostSuccessMessage(),
                "Account reclaim by post was requested",
                "Check message displayed when account reclaim is a success");
    }

    @Test(groups = {"VM-9085", "slice_A"})
    public void testCSCOCanCancelAccountReclaimAndReturnToUserProfile() {

        HelpDeskUserProfilePage helpDeskUserProfilePage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterUsername(login.username).search().clickUserName(0).resetAccountByPost()
                .clickCancelAndReturnToUserProfileLink();

        assertFalse(helpDeskUserProfilePage.isAccountReclaimASuccess(),
                "Check to ensure account wasn't reclaimed");
    }

    @Test(groups = {"VM-9085", "slice_A"}) public void testAccountCantBeReclaimedMoreThanOnce() {

        HelpDeskUserProfilePage helpDeskUserProfilePage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterUsername(login.username).search().clickUserName(0).resetAccountByPost()
                .clickReclaimAccountButton().resetAccountByPost().clickReclaimAccountButton();

        assertTrue(helpDeskUserProfilePage.isAccountReclaimAFailure(),
                "Check to ensure account can't be reclaimed more than once");
        assertEquals(helpDeskUserProfilePage.getAccountReclaimByFailureMessage(),
                "A request to re-set this user's account has already been made today",
                "Check message displayed when account reclaim attempt is more than once");
    }
}
