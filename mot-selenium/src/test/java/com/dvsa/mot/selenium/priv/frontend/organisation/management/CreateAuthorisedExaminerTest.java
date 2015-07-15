package com.dvsa.mot.selenium.priv.frontend.organisation.management;

import com.dvsa.mot.selenium.datasource.Business;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.CreateAuthorisedExaminerPage;
import org.testng.Assert;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class CreateAuthorisedExaminerTest extends BaseTest {

    private String notificationForAEDMlinkText =
            "Authorised Examiner Designated Manager role notification";

    @Test(groups = {"FeatureToggleCreateAe"}) public void createAe() {
        //AuthorisedExaminerOverviewPage overViewOfAe =
        CreateAuthorisedExaminerPage createAuthorisedExaminerPage = CreateAuthorisedExaminerPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1)
                .fillBusinessDetailsAndBUtypeRegCompany(Business.BUSINESS_6)
                .fillBusinessContactDetails(Business.BUSINESS_6).selectBussNoEmailOption();

        assertThat("Assert that Email address is null",
                createAuthorisedExaminerPage.isBusinessEmailNull());
        assertThat("Assert that Confirm Email address is null",
                createAuthorisedExaminerPage.isBusinessConfirmEmailNull());
        createAuthorisedExaminerPage.fillBusinessEmail(Business.BUSINESS_6);

        assertThat("Assert that Email option is disabled",
                createAuthorisedExaminerPage.isBussEmailOptionSelected(),
                is(false));
        createAuthorisedExaminerPage.selectBusinessDetailsDiff().fillCorresPondenceContactDetails(Business.BUSINESS_4)
                .selectCorrNoEmailOption();

        assertThat("Assert that Correspondence Email address is null",
                createAuthorisedExaminerPage.isCorrEmailNull());
        assertThat("Assert that Correspondence Confirm Email address is null",
                createAuthorisedExaminerPage.isCorrConfirmEmailNull());

        createAuthorisedExaminerPage.fillCorrEmail(Business.BUSINESS_4);

        assertThat("Assert that Correspondence Email option is disabled",
                createAuthorisedExaminerPage.isCorrEmailOptionSelected(), is(false));

        createAuthorisedExaminerPage.selectBusinessDetailsSame()
                .selectBusinessDetailsDiff();
        Assert.assertEquals(createAuthorisedExaminerPage.getCorrespondenceAddress1(),
                Business.BUSINESS_4.busAddress.getLine1());
        Assert.assertEquals(createAuthorisedExaminerPage.getCorrespondenceAddress2(),
                Business.BUSINESS_4.busAddress.getLine2());
        Assert.assertEquals(createAuthorisedExaminerPage.getCorrespondenceAddress3(),
                Business.BUSINESS_4.busAddress.getLine3());
        Assert.assertEquals(createAuthorisedExaminerPage.getCorrespondenceTown(),
                Business.BUSINESS_4.busAddress.getTown());
        Assert.assertEquals(createAuthorisedExaminerPage.getCorrespondencePostCode(),
                Business.BUSINESS_4.busAddress.getPostcode());
        Assert.assertEquals(createAuthorisedExaminerPage.getCorrespondencePhoneNumber(),
                Business.BUSINESS_4.busDetails.phoneNo);
        Assert.assertEquals(createAuthorisedExaminerPage.getCorrespondenceEmail(),
                Business.BUSINESS_4.busDetails.emailAdd);
        Assert.assertEquals(createAuthorisedExaminerPage.getCorrespondenceConfirmEmail(),
                Business.BUSINESS_4.busDetails.emailAdd);
        //.fillBusinessContactDetails(Business.BUSINESS_1 )
        //.fillCorresPondenceContactDetails(Business.BUSINESS_4).clickOnSaveButton();
    }

    /*@Test(groups = {"VM-2166", "Sprint-23", "LA-2"})
    public void testCreateAuthorisedExaminerAndSelectBusinessTypeAsRegisteredCompany() {
    	AuthorisedExaminerOverviewPage overViewOfAe = CreateAuthorisedExaminerPage.navigateHereFromLoginPage(driver, Login.LOGIN_SCHEME_MANAGEMENT)
    				.fillBusinessDetailsAndBUtypeRegCompany(Business.BUSINESS_6)
    				.fillBusinessContactDetails(Business.BUSINESS_1 )
    				.fillCorresPondenceContactDetails(Business.BUSINESS_4).clickOnSaveButton();
    	assertThat("AE Created", overViewOfAe.isAuthorisedExaminerNameAndUniquNumberPresent(Business.BUSINESS_6),is(true));
    	Assert.assertTrue(overViewOfAe.checkAddress(Business.BUSINESS_1.busAddress));
    	overViewOfAe.clickLogout();
    }

    @Test(groups = {"VM-2166", "Sprint-23", "LA-2"})
    public void testCreateAuthorisedExaminerWithOutCorrespondenceDetails() {
    	AuthorisedExaminerOverviewPage overViewOfAE = CreateAuthorisedExaminerPage.navigateHereFromLoginPage(driver, Login.LOGIN_SCHEME_MANAGEMENT)
    				.fillBusinessDetailsAndBuTypePartnership(Business.BUSINESS_1)
    				.fillBusinessContactDetails(Business.BUSINESS_1 )
    				.clickOnSameAsBusinessContactDetails().clickOnSaveButton();
    	assertThat("AE Created", overViewOfAE.isAuthorisedExaminerNameAndUniquNumberPresent(Business.BUSINESS_1),is(true));
    	overViewOfAE.clickLogout();
    }

    @Test(groups = {"VM-2166", "Sprint-23", "LA-2"})
    public void testVerifyBusinessTypeRequiredFieldErrorMessage() {
        CreateAuthorisedExaminerPage createAEdetails = CreateAuthorisedExaminerPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_SCHEME_MANAGEMENT);
        createAEdetails.fillBusinessDetailsAndBUtypeLimitedLiabilityPartnership(Business.BUSINESS_3)
                .selectNoBusinessType().fillBusinessContactDetails(Business.BUSINESS_1)
                .fillCorresPondenceContactDetails(Business.BUSINESS_4).clickOnSaveButton();
        assertThat("Error Message Displayed for AE Business Type blank", createAEdetails.isCreationAEWithoutBusinessType(),
                is(true));
        createAEdetails.clickLogout();
    }
    //TODO could be removed if "createTester" returns a Person object or if it exists a "createPerson" method
    private LoginPage loginAsAdminAndAssignAEDMRoleForNomination(String username) {
        AuthorisedExaminerOverviewPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_SCHEME_MANAGEMENT,
                        Business.NEED_4_SPEED).clickAssignRole().enterUsername(username).search()
                .selectRole(Role.AEDM).clickAssignARoleButton()
                .clickOnConfirmButton().
                clickLogout();
        return new LoginPage(driver);
    }

    @Test(groups = {"VM-2166", "Sprint-23", "LA-2"})
    public void testToCheckCreateAEwithMandatoryFields() {
        AuthorisedExaminerOverviewPage overViewOfAE = CreateAuthorisedExaminerPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_SCHEME_MANAGEMENT)
                .fillBusinessDetailsAndBUtypeSoleTrader(Business.BUSINESS_2)
                .fillBusinessContactDetails(Business.BUSINESS_1)
                .fillCorresPondenceContactDetails(Business.BUSINESS_4)
                .checkCreateAEDetailsWithMandatoryFields(Business.BUSINESS_1).clickOnSaveButton();
        assertThat("AE Sole Trader trading type present", overViewOfAE.isAuthorisedExaminerBusinessTypePresent("Sole Trader"), is(true));
        overViewOfAE.clickLogout();
    }

    private AssignARoleConfirmationPage loginAsAdminAndAssignAEDMRoleToSameAEDM(String username) {
        return AuthorisedExaminerOverviewPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_SCHEME_MANAGEMENT,
                        Business.NEED_4_SPEED).clickAssignRole().enterUsername(username).search()
                .selectRole(Role.AEDM).clickAssignARoleButton()
                .clickOnConfirmNominationExpectingError();
    }

    @Test(groups = {"VM-2370","VM-2166", "Sprint-23", "LA-2"})
    public void testUpdateAEBusinessNameAndContactDetails() {
        AuthorisedExaminerOverviewPage overViewOfAE = CreateAuthorisedExaminerPage.navigateHereFromLoginPage(driver, Login.LOGIN_SCHEME_MANAGEMENT)
                .fillBusinessDetailsAndBUtypeRegCompany(Business.BUSINESS_6)
                .fillBusinessContactDetails(Business.BUSINESS_1)
                .fillCorresPondenceContactDetails(Business.BUSINESS_4).clickOnSaveButton();

        CreateAuthorisedExaminerPage updateAEdetails = new CreateAuthorisedExaminerPage(driver);
                overViewOfAE.clickOnChangeAEDetailsLink();
        updateAEdetails.updateNewBusinessDetails(Business.BUSINESS_5)
                .updateNewBusinessContactDetails(Business.BUSINESS_5)
                .updateNewCorrespondenceContactDetails(Business.BUSINESS_5)
                .clickOnSaveButton();
        assertThat("Updated AE Business Details and Contact Details are present",overViewOfAE.isBusinessDetailsAndContactDetailsUpdated(Business.BUSINESS_5), is(true));
        overViewOfAE.clickLogout();
        }

        @Test(groups = {"VM-2834", "LA-2"})
        public void testAssignAEDMtoAnAE() {
            loginAsAdminAndAssignAEDMRoleForNomination(login.username);
            UserDashboardPage userDashboard = loginAsAEDM(login.username, login.password);
            NotificationPage notificationsPage = userDashboard.clickNotification(notificationForAEDMlinkText);
            assertThat("AEDM Received Notification of Accepted Nomination", notificationsPage.getNotificationMessageForAEDM(), is(Notifications.ASSERTION_AEDM_ROLE_NOTIFICATION.assertion));
            notificationsPage.backToHomePage();
            assertThat(" AE details are not empty", userDashboard.verifyAEdetailsPresent(), is(true));
            userDashboard.clickLogout();
            testErrorMessageForDuplicateAssignAEDMtoAnAE();
        }

        public void testErrorMessageForDuplicateAssignAEDMtoAnAE() {
            loginAsAdminAndAssignAEDMRoleToSameAEDM(login.username);
            AssignARoleConfirmationPage assignARoleConfirmationPage = new AssignARoleConfirmationPage(driver);
            assertThat("Error message displayed for duplicate assign AEDM to an AE", assignARoleConfirmationPage.isCreationAEWithoutBusinessType(),is(true));
            assignARoleConfirmationPage.clickLogout();
        }

        private UserDashboardPage loginAsAEDM(String UserId, String password) {
            return UserDashboardPage.navigateHereFromLoginPage(driver, new Login(UserId, password));
        }*/
}

