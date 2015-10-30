package com.dvsa.mot.selenium.priv.frontend.helpdesk;

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
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import java.util.ArrayList;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;
import static org.testng.Assert.*;

public class HelpDeskTests extends BaseTest {

    private AeService aeService = new AeService();

    @Test(groups = {"VM-4880", "VM-4881", "VM-4842", "VM-7724", "V-Sprint10", "W-Sprint5",
            "Regression", "slice_D"}, description = "Verify ResetPassword functionality")
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

        System.out.println(person.getNamesAndSurname());

        assertEquals(helpdeskResetPasswordSuccessPage.getFirstTimeResetPasswordConfirmationText(),
                "A letter will be sent to " + person.getFirstAndSurname()
                        + " giving instructions on how to reset the password for their account.",
                "Check the correct message is displayed when reset password is triggered by post");
    }

    @Test(groups = {"VM-4880", "VM-4881", "VM-4842", "VM-7724", "V-Sprint10", "W-Sprint5",
            "Regression", "slice_D"}, description = "Verify RecoverUserName functionality")
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
                "A letter will be sent to " + person.getFirstAndSurname()
                        + " containing the username for their MOT account.",
                "Check the correct message is displayed when username recovery is triggered by post");
    }

    @Test(groups = {"VM-4880", "VM-4842", "VM-7724", "V-Sprint10", "W-Sprint5", "Regression",
            "slice_D"}, description = "Verify ReturnToHome functionality in the user profile page")
    public void testGoToUserProfilePageAndGoToHome() {
        Login user = createTester(true);
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver).navigateToClaimAccountPage(driver, user);
        openAMClaimAccountSignInPage.submitEmailSuccessfully(
                Person.getUnique(Person.PERSON_1, "loginIntoOpenAMClaimAccount"))
                .enterNewPassword(Text.TEXT_RESET_PASSWORD)
                .enterNewConfirmPassword(Text.TEXT_RESET_PASSWORD).clickOnSubmitButton()
                .submitSecurityQuestionAndAnswersSuccessfully()
                .clickClaimYourAccoutButton()
                .clickContinueToTheMotTestingService().clickLogout();
        UserDashboardPage userDashboardPage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterUsername(user.username).search().clickUserName(0).returnToHome();

        assertTrue(userDashboardPage.isHelpdeskSearchUserLinkClickable(),
                "Check to ensure CSCO is navigated back to home page");
    }

    @DataProvider(name = "usersNotAllowedHelpdeskUserProfile")
    public Object[][] usersNotAllowedHelpdeskUserProfile() {
        return new Object[][] {{Login.LOGIN_TESTER1}, {Login.LOGIN_AEDM},
                {Login.LOGIN_SCHEME_MANAGEMENT}, {Login.LOGIN_AREA_OFFICE1}};
    }

    @Test(groups = {"VM-7295", "Regression"}, dataProvider = "dvsaUserCanSearchForAUser")
    public void testUserCanClickOnEventsHistoryLink(Login login) {
        EventHistoryPage eventHistoryPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login).clickUserSearch()
                        .enterUsername("tester1").search().clickUserName(0).clickEventHistoryLink();

        assertTrue(eventHistoryPage.checkTableExists(),
                "Check if event history table page is displayed");
    }

    @Test(groups = {"VM-8049", "Regression"}, dataProvider = "dvsaUserCanSearchForAUser")
    public void testDvsaUserCanViewVtsAssociationSuccessfully(Login login) {
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);
        userDashboardPage.clickUserSearch().enterUsername("tester1").search().clickUserName(0)
                .getVehicleTestingStationPage();
        SiteDetailsPage siteDetailsPage = new SiteDetailsPage(driver);

        assertThat("Vehicle Testing Station page is displayed",
                siteDetailsPage.checkChangeOpeningHoursLinkExists(), is(true));
    }

    @Test(groups = {"VM-8049", "Regression"}, dataProvider = "dvsaUserCanSearchForAUser")
    public void testDvsaUserCanViewAeAssociationSuccessfully(Login login) {

        AuthorisedExaminerFullDetailsPage authorisedExaminerFullDetailsPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login).clickUserSearch()
                        .enterUsername("aedm").search().clickUserName(0)
                        .getAuthorisedExaminerPage();

        assertThat("Full Details of Authorised Examiner page is displayed",
                authorisedExaminerFullDetailsPage.checkSearchAgainLinkExists(), is(true));
    }

    @Test(groups = {"VM-8049", "Regression"})
    public void testCscoUserCanViewVtsAssociationSuccessfully() {
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE);
        userDashboardPage.clickUserSearch().enterUsername("tester1").search().clickUserName(0);
        SiteDetailsPage siteDetailsPage =
                new HelpDeskUserProfilePage(driver).getVehicleTestingStationPage();

        assertThat("Vehicle Testing Station page is displayed without change opening hours link",
                siteDetailsPage.checkChangeOpeningHoursLinkExists(), is(false));
    }

    @Test(groups = {"VM-9084", "Regression"})
    public void testCSCOCanSearchForUsersWithUnclaimedAccounts() {
        Login unclaimedAccountUser = createTester(true);
        HelpDeskUserProfilePage helpDeskUserProfilePage =
                UserDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                        .clickUserSearch().enterUsername(unclaimedAccountUser.username).search()
                        .clickUserName(0);

        assertEquals(helpDeskUserProfilePage.getUserName(), unclaimedAccountUser.username,
                "Check that username searched is displayed on profile page");
    }

    @Test(groups = {"VM-9085", "Regression"}) public void testCSCOCanReclaimAccountByPost() {

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

    @Test(groups = {"VM-9085", "Regression"})
    public void testCSCOCanCancelAccountReclaimAndReturnToUserProfile() {

        HelpDeskUserProfilePage helpDeskUserProfilePage = HelpdeskUserSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterUsername(login.username).search().clickUserName(0).resetAccountByPost()
                .clickCancelAndReturnToUserProfileLink();

        assertFalse(helpDeskUserProfilePage.isAccountReclaimASuccess(),
                "Check to ensure account wasn't reclaimed");
    }

    @Test(groups = {"VM-9085", "Regression"}) public void testAccountCantBeReclaimedMoreThanOnce() {

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
