package com.dvsa.mot.selenium.priv.frontend.organisation.management;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.enums.Role;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.*;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.framework.errors.UnauthorisedError;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.common.FindAUserPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.testng.Assert;
import org.testng.annotations.Test;

import java.util.Arrays;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class AuthorisedExaminerOverviewTest extends BaseTest {
    private Business organisation = Business.EXAMPLE_AE_INC;
    private  Role role = Role.AED;

    @Test(groups = {"VM-2218", "VM-2226", "VM-2368", "slice_A", "VM-2223", "VM-2296"})
    public void testAllAEDetailsDisplayedOnThePage() {
        int aeId = createAE("testAllAEDetailsDisplayedOnThePage");
        createVTS(aeId, null, Login.LOGIN_AREA_OFFICE1, "testAllAEDetailsDisplayedOnThePage");
        Login aedmLogin = createAEDM(aeId, Login.LOGIN_AREA_OFFICE2, false);

        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, aedmLogin);

        //testAuthorisedExaminerAddress
        assertThat("address present",
                authorisedExaminerOverviewPage.isAuthorisedExaminerDetailsPresent(), is(true));

        //testAuthorisedExaminerVTSDetails
        assertThat("VTS details present", authorisedExaminerOverviewPage
                .isAuthorisedExaminerVehicleTestingStationDetailsPresent(), is(true));

        //testViewSlotsBalance
        assertThat("View Available slots",
                authorisedExaminerOverviewPage.isSlotsZeroOrMoreAvailable(), is(true));

        //testAssignARoleToAEAndVerifyThatLinkExists
        assertThat("AE management Role exists",
                authorisedExaminerOverviewPage.verifyAeManagementRolesExists(), is(true));


        //testToCheckInvalidUserCannotBeAssignedARole
        FindAUserPage findAUserPage = authorisedExaminerOverviewPage.clickAssignRole()
                .enterUsername(Login.LOGIN_INVALID_USERNAME.username).searchExpectingError();
        Assert.assertEquals(findAUserPage.getInvalidUserMessage(),
                Assertion.ASSERTION_NO_USER_EXISTS.assertion + Login.LOGIN_INVALID_USERNAME.username
                        + ".");
    }

    @Test(groups = {"slice_A"}, expectedExceptions = UnauthorisedError.class)
    public void testerCannotSeeDetailsOfAeHeIsNotAssignedTo() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("testerCannotSeeAE");
        /* Create a tester who is not associated to any AE*/
        Login testerLogin = createTester();
        LoginPage.loginAs(driver, testerLogin);
        GoToTheUrl.goToTheAeOverviewPage(driver, aeDetails.getId());
    }

    @Test(groups = {"Spring-24", "VM-2225", "VM-2223", "VM-2224", "VM-2525", "slice_A"},
            priority = 1,
            
            description = "Remove role from Authorised Examiner Page")
    public void testRemoveRoleFromAuthorisedExaminer() {

        //Create AE
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("AERemoverole");
        String siteName = "VTS_";

        //Create VTS
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        siteName);
        //Create Tester
        TesterCreationApi testerCreationApi = new TesterCreationApi();
        Person person = testerCreationApi.createTesterAsPerson(Arrays.asList(site.getId()));

        //Create AEDM
        AedmCreationApi aedmCreationApi = new AedmCreationApi();
        Person newAedm = aedmCreationApi.createAedmAsPerson(aeDetails.getId());

        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, newAedm.login);
        authorisedExaminerOverviewPage.assignNewRoleToUser(role, person).clickLogout();
        UserDashboardPage.navigateHereFromLoginPage(driver, person.login)
                .clickNotification(role.getShortName() + " nomination").clickAcceptNomination()
                .clickLogout();

        authorisedExaminerOverviewPage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, newAedm.login);

        Assert.assertTrue(authorisedExaminerOverviewPage.existAEDMRoleForUser(role, person));

        authorisedExaminerOverviewPage.removeAEDMRoleFromUser(role, person);

        Assert.assertTrue(authorisedExaminerOverviewPage.getInfoMessage().contains(
                "You have removed the role of AUTHORISED-EXAMINER-DELEGATE from " + person
                        .getFullNameWithOutTitle()), "aed role is disassociated");
        authorisedExaminerOverviewPage.clickLogout();
        UserDashboardPage.navigateHereFromLoginPage(driver, person.login).clickRoleRemovalLink()
                .getRemovalNotificationMessage();
    }
}    
