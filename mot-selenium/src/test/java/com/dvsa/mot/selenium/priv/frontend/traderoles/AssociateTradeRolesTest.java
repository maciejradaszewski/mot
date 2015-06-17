package com.dvsa.mot.selenium.priv.frontend.traderoles;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Notifications;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.enums.Role;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.pub.frontend.application.tester.pages.NotificationPage;
import org.apache.commons.lang3.RandomStringUtils;
import org.testng.annotations.Test;

import java.util.ArrayList;
import java.util.Arrays;

import static com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AEAssignARoleConfirmationPage.assignRoleAtOrganisationLevel;
import static com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages.VTSAssignARoleConfirmationPage.assignRoleAtSiteLevel;
import static org.testng.Assert.assertEquals;
import static org.testng.Assert.assertTrue;

public class AssociateTradeRolesTest extends BaseTest {

    /**
     * As a AEDM user I am assigning AED role to a tester on an AE level.
     */
    @Test(groups = {"VM-8593", "slice_A"}) public void assignAEDRoleOnAELevel() {

        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("ATRWheels");
        String vtsName = RandomStringUtils.randomAlphabetic(6);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login aedm = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);
        Person tester = createTesterAsPerson(Arrays.asList(site.getId()));
        Login testerLogin = tester.getLogin();
        String organisation = "TEST ORGANISATION ATRWHEELS";

        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage =
                assignRoleAtOrganisationLevel(aedm, testerLogin, site, organisation, driver,Role.AED)
                        .clickOnConfirmButton();

        assertTrue(authorisedExaminerOverviewPage.existAEDMRoleForUser(Role.AED, tester),
                "AED role was NOT assigned to a tester");

        LoginPage loginPage = authorisedExaminerOverviewPage.clickLogout();

        // login as a tester to check for nomination letter
        UserDashboardPage userDashboardPage = loginPage.loginAsUser(tester.login);
        NotificationPage notificationPage =
                userDashboardPage.clickNotification("Authorised Examiner Delegate nomination");
        notificationPage = notificationPage.clickAcceptNominationForAED();
        assertEquals(notificationPage.getNominationDecisionMessage(),
                Notifications.ASSERTION_ACCEPT_NOMINATION_DECISION.assertion + " " + "'"
                        + Notifications.ASSERTION_AED_ROLE.assertion + "'" + " at " + "'"
                        + "Test Organisation ATRWheels" + " " + aeDetails.getAeRef() + "'" + ".",
                "Acceptance of the nomination letter was not successful");
    }

    /**
     * As a AEDM user I am assigning Site Mgr role to a tester on VTS level.
     */
    @Test(groups = {"VM-8593", "slice_A"}) public void testAssignSiteMgrRoleOnVTSLevel() {

        int aeId = createAE("ATRWheels1");
        String vtsName = "ATRGarage1";
        int vtsId = createVTS(aeId, TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login aedm = createAEDM(aeId, Login.LOGIN_AREA_OFFICE2, false);
        Person tester = createTesterAsPerson(Arrays.asList(vtsId));
        SiteDetailsPage siteDetailsPage =
                assignRoleAtSiteLevel(aedm, vtsName, tester, Role.SITE_MANAGER, driver)
                        .clickOnConfirmNominationExpectingSiteDetailsPage();

        assertTrue(siteDetailsPage.existVTSRoleForUser(Role.SITE_MANAGER, tester),
                Role.SITE_MANAGER + " role was NOT assigned to a " + tester);

        LoginPage loginPage = siteDetailsPage.clickLogout();
        NotificationPage notificationPage = NotificationPage
                .loginAsAssociatedUserToCheckNominationLetter(loginPage, tester,
                        "Site manager nomination");

        assertEquals(notificationPage.getNominationDecisionMessage(),
                Notifications.ASSERTION_ACCEPT_NOMINATION_DECISION.assertion + " " + "'"
                        + Notifications.ASSERTION_SITE_MANAGER_ROLE.assertion + "'" + " at " + "'"
                        + "Test Site " + vtsName + " " + "S00" + vtsId + "'" + ".",
                "Acceptance of the nomination letter was not successful");
    }

    /**
     * As a AEDM user I am assigning Site Admin role to a tester on VTS level.
     */
    @Test(groups = {"VM-8593", "slice_A"}) public void testAssignSiteAdminRoleOnVTSLevel() {
        int aeId = createAE("ATRWheels2");
        String vtsName = "ATRGarage2";
        int vtsId = createVTS(aeId, TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login aedm = createAEDM(aeId, Login.LOGIN_AREA_OFFICE2, false);
        Person tester = createTesterAsPerson(Arrays.asList(vtsId));
        SiteDetailsPage siteDetailsPage =
                assignRoleAtSiteLevel(aedm, vtsName, tester, Role.SITE_ADMIN, driver)
                        .clickOnConfirmNominationExpectingSiteDetailsPage();

        assertTrue(siteDetailsPage.existVTSRoleForUser(Role.SITE_ADMIN, tester),
                Role.SITE_ADMIN + " role was NOT assigned to a " + tester);

        LoginPage loginPage = siteDetailsPage.clickLogout();
        NotificationPage notificationPage = NotificationPage
                .loginAsAssociatedUserToCheckNominationLetter(loginPage, tester,
                        "Site admin nomination");

        assertEquals(notificationPage.getNominationDecisionMessage(),
                Notifications.ASSERTION_ACCEPT_NOMINATION_DECISION.assertion + " " + "'"
                        + Notifications.ASSERTION_SITE_ADMIN_ROLE.assertion + "'" + " at " + "'"
                        + "Test Site " + vtsName + " " + "S00" + vtsId + "'" + ".",
                "Acceptance of the nomination letter was not successful");
    }

    /**
     * As a Site Mgr user I am assigning Site Admin role to a tester on VTS level.
     */
    @Test(groups = {"VM-8593", "slice_A"})
    public void testAssignSiteAdminRoleOnVTSLevelAsSiteMgr() {

        int aeId = createAE("ATRWheels3");
        String vtsName = "ATRGarage3";
        int vtsId = createVTS(aeId, TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login smgr = createSM(Arrays.asList(vtsId), Login.LOGIN_AREA_OFFICE2, null);
        Person tester = createTesterAsPerson(Arrays.asList(vtsId));
        SiteDetailsPage siteDetailsPage =
                assignRoleAtSiteLevel(smgr, vtsName, tester, Role.SITE_ADMIN, driver)
                        .clickOnConfirmNominationExpectingSiteDetailsPage();

        assertTrue(siteDetailsPage.existVTSRoleForUser(Role.SITE_ADMIN, tester),
                Role.SITE_ADMIN + " role was NOT assigned to a " + tester);

        LoginPage loginPage = siteDetailsPage.clickLogout();
        NotificationPage notificationPage = NotificationPage
                .loginAsAssociatedUserToCheckNominationLetter(loginPage, tester,
                        "Site admin nomination");

        assertEquals(notificationPage.getNominationDecisionMessage(),
                Notifications.ASSERTION_ACCEPT_NOMINATION_DECISION.assertion + " " + "'"
                        + Notifications.ASSERTION_SITE_ADMIN_ROLE.assertion + "'" + " at " + "'"
                        + "Test Site " + vtsName + " " + "S00" + vtsId + "'" + ".",
                "Acceptance of the nomination letter was not successful");
    }

    @Test(groups = {"VM-9042", "slice_A"}) public void testVtsAssociationDisplayOnHomePage() {

        String siteManagerNotification = "Site manager nomination";
        AeService aeService = new AeService();
        String aeName = RandomStringUtils.randomAlphabetic(6);
        String firstVtsName = RandomStringUtils.randomAlphabetic(6);
        String secondVtsName = RandomStringUtils.randomAlphabetic(6);
        AeDetails aeDetails = aeService.createAe(aeName);
        ArrayList vts = new ArrayList();
        int firstVtsId =
                createVTS(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, firstVtsName);
        int secondVtsId = createVTS(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                secondVtsName);
        vts.add(firstVtsId);
        vts.add(secondVtsId);
        Login aedm = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);
        Person testerBecomesSiteManager = createTesterAsPerson(vts);
        Login siteManager = testerBecomesSiteManager.getLogin();

        SiteDetailsPage siteDetailsPageOne =
                assignRoleAtSiteLevel(aedm, firstVtsName, testerBecomesSiteManager,
                        Role.SITE_MANAGER, driver)
                        .clickOnConfirmNominationExpectingSiteDetailsPage();
        LoginPage loginPageOne = siteDetailsPageOne.clickLogout();
        UserDashboardPage userDashboardPageOne = loginPageOne.loginAsUser(siteManager);
        userDashboardPageOne.clickNotification(siteManagerNotification).clickAcceptNomination()
                .clickLogout();

        SiteDetailsPage siteDetailsPageTwo =
                assignRoleAtSiteLevel(aedm, secondVtsName, testerBecomesSiteManager,
                        Role.SITE_MANAGER, driver)
                        .clickOnConfirmNominationExpectingSiteDetailsPage();
        LoginPage loginPageTwo = siteDetailsPageTwo.clickLogout();
        UserDashboardPage userDashboardPageTwo = loginPageTwo.loginAsUser(siteManager);
        userDashboardPageTwo.clickNotification(siteManagerNotification).clickAcceptNomination()
                .clickLogout();

        UserDashboardPage userDashboardPageThree =
                UserDashboardPage.navigateHereFromLoginPage(driver, siteManager);

        assertTrue(userDashboardPageThree.isVtsAssociationDisplayed(firstVtsName),
                "Check if vts association is displayed on home page");
        assertTrue(userDashboardPageThree.isVtsAssociationDisplayed(secondVtsName),
                "Check if vts association is displayed on home page");
    }
}
