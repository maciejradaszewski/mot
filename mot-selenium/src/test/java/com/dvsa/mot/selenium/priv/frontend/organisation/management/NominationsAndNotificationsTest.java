package com.dvsa.mot.selenium.priv.frontend.organisation.management;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.enums.Days;
import com.dvsa.mot.selenium.datasource.enums.Role;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.Utilities;
import com.dvsa.mot.selenium.framework.api.AedCreationApi;
import com.dvsa.mot.selenium.framework.api.MotTestApi;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EventHistoryPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.ManageOpeningHoursPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpdeskUserResultsPage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpdeskUserSearchPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.notifications.pages.NotificationsRoleRemovalPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.pub.frontend.application.tester.pages.NotificationPage;
import org.joda.time.DateTime;
import org.joda.time.format.DateTimeFormat;
import org.testng.Assert;
import org.testng.annotations.Test;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;

import static com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AEAssignARoleConfirmationPage.assignRoleAtOrganisationLevel;
import static com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages.VTSAssignARoleConfirmationPage.assignMultipleRoleAtSiteLevel;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;
import static org.testng.Assert.*;

public class NominationsAndNotificationsTest extends BaseTest {

    private String nominationForAEDLinkText = "Authorised Examiner Delegate nomination";
    private String siteManager = "Site manager nomination";
    private String siteAdmin = "Site admin nomination";
    private String tester = "Tester nomination";
    private String fakeTestSurname = "FakeTest Surname-";
    private AeService aeService = new AeService();

    private int getUnreadNotificationForSiteAssociation(Login traderLogin) {

        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, traderLogin);
        int unreadNotificationsAssignedUser = userDashboardPage.getNumberOfUnreadNotifications();
        userDashboardPage.clickLogout();

        return unreadNotificationsAssignedUser + 3;

    }

    private int getUnreadNotificationForOrganisationAssociation(Login traderLogin) {

        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, traderLogin);
        int unreadNotificationsAssignedUser = userDashboardPage.getNumberOfUnreadNotifications();
        userDashboardPage.clickLogout();

        return unreadNotificationsAssignedUser + 1;
    }

    @Test(groups = {"Sprint-22", "Regression", "VM-5022", "VM-5024"})

    public void testListOfNominationsAtSiteLevel() {

        AeDetails aeDetails = aeService.createAe("AE_");
        String siteName = "VTS_";
        String siteNameTwo = "VTS_TWO";
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        siteName);
        Site siteTwo = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        siteNameTwo);
        Login responseData = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);
        Login aedmLogin = new Login(responseData.username, responseData.password);
        Person trader = createTesterAsPerson(Arrays.asList(site.getId()));
        Login traderLogin = trader.getLogin();
        ArrayList<Role> roles = new ArrayList<>();
        int unreadNotificationsAssignedUser = getUnreadNotificationForSiteAssociation(traderLogin);

        roles.add(Role.TESTER);
        roles.add(Role.SITE_MANAGER);
        roles.add(Role.SITE_ADMIN);
        SiteDetailsPage siteDetailsPage =
                assignMultipleRoleAtSiteLevel(aedmLogin, siteNameTwo, trader, roles, driver);

        assertTrue(siteDetailsPage.verifyNominationMessage(trader),
                "Check that the nomination message is displayed on VTS page");

        siteDetailsPage.clickLogout();
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, traderLogin);

        assertEquals(userDashboardPage.getNumberOfUnreadNotifications(),
                (unreadNotificationsAssignedUser),
                "Check the number of notifications displayed on home page");

        userDashboardPage.clickNotification(siteManager).clickAcceptNomination().backToHomePage()
                .clickNotification(siteAdmin).clickAcceptNomination().backToHomePage()
                .clickNotification(tester).clickAcceptNomination();
        NotificationPage notificationsPage = new NotificationPage(driver);

        assertEquals(notificationsPage.getNominationDecisionMessage(),
                (Notifications.ASSERTION_ACCEPT_NOMINATION_DECISION.assertion + " " + "'"
                        + Notifications.ASSERTION_TESTER_ROLE.assertion + "'" + " at " + "'"
                        + siteTwo.getName() + " " + siteTwo.getNumber() + "'" + "."),
                "Accepted Nomination");


        notificationsPage.clickLogout();
        notificationsPage = NotificationPage.navigateHereFromLogin(driver, aedmLogin,
                Text.TEXT_NOTIFICATION_NOMINATION_ACCEPTED.text);

        assertEquals(notificationsPage.getNotificationTypeByNomination(),
                (Notifications.ASSERTION_ACCEPTED_NOMINATION_NOTIFICATION.assertion),
                "AEDM Received Notification of Accepted Nomination");

        notificationsPage.backToHomePage();
        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage =
                userDashboardPage.manageAuthorisedExaminer(aeDetails.getId());

        assertFalse(authorisedExaminerOverviewPage
                        .verifyUserAddedToAEDList(fakeTestSurname, trader.getFullName()),
                "Check to ensure user not added to AED list on AE page");

        SiteDetailsPage siteDetailsPageOne =
                authorisedExaminerOverviewPage.clickVTSLinkExpectingSiteDetailsPage(site.getName());
        DateTime nominationResponse = new DateTime();
        String expectedDate = nominationResponse.toString(DateTimeFormat.forPattern("d MMM yyyy"));

        assertTrue(siteDetailsPageOne.isNominationResponseDateReflected(nominationResponse),
                "Check the date on the nomination message");

        siteDetailsPageOne.clickLogout();
        Login veLogin = createVE();
        HelpdeskUserResultsPage helpdeskUserResultsPage =
                HelpdeskUserSearchPage.navigateHereFromLoginPage(driver, veLogin)
                        .enterUsername(traderLogin.username).search();
        EventHistoryPage helpdeskUserProfilePage =
                helpdeskUserResultsPage.clickUserName(0).clickEventHistoryLink();

        assertEquals(helpdeskUserProfilePage.getEventType(), "Role Association Change",
                "Check the event type displayed");
        assertEquals(helpdeskUserProfilePage.getEventDate().split(",")[0], expectedDate,
                "Check the date of the event displayed");
        assertTrue((Utilities.getTimeDifference(helpdeskUserProfilePage.getEventDate().split(",")[1])) <= 5,
                "Check the time difference of the event created is less than 5 minutes");
    }

    @Test(groups = {"Sprint-22", "Regression", "VM-5022", "VM-5024"})
    public void testListOfNominationsAtOrgLevel() {

        AeDetails aeDetails = aeService.createAe("AE_");
        String siteName = "VTS_";
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        siteName);
        Login responseData = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);
        Login aedmLogin = new Login(responseData.username, responseData.password);
        Person trader = createTesterAsPerson(Arrays.asList(site.getId()));
        Login traderLogin = trader.getLogin();
        int unreadNotificationsAssignedUser =
                getUnreadNotificationForOrganisationAssociation(traderLogin);
        String organisation = "TEST ORGANISATION AE_";

        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage =
                assignRoleAtOrganisationLevel(aedmLogin, traderLogin, site, organisation, driver,
                        Role.AED).clickOnConfirmButton();

        assertTrue(authorisedExaminerOverviewPage.verifyNominationMessage(trader),
                "Check that the nomination message is displayed on AE page");

        authorisedExaminerOverviewPage.clickLogout();
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, traderLogin);

        assertEquals(userDashboardPage.getNumberOfUnreadNotifications(),
                (unreadNotificationsAssignedUser),
                "Check the number of notifications displayed on home page");

        userDashboardPage.clickNotification(nominationForAEDLinkText).clickRejectNominationForAED();
        NotificationPage notificationsPage = new NotificationPage(driver);

        assertEquals(notificationsPage.getNominationDecisionMessage(),
                (Notifications.ASSERTION_REJECT_NOMINATION_DECISION.assertion + " " + "'"
                        + Notifications.ASSERTION_AED_ROLE.assertion + "'" + " at " + "'"
                        + "Test Organisation AE_" + " " + aeDetails.getAeRef() + "'" + "."),
                "Rejected Nomination");

        notificationsPage.clickLogout();
        notificationsPage = NotificationPage.navigateHereFromLogin(driver, aedmLogin,
                Text.TEXT_NOTIFICATION_NOMINATION_REJECTED.text);

        assertEquals(notificationsPage.getNotificationTypeByNomination(),
                (Notifications.ASSERTION_REJECTED_NOMINATION_NOTIFICATION.assertion),
                "AEDM Received Notification of Rejected Nomination");

        notificationsPage.backToHomePage();
        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPageOne =
                userDashboardPage.manageAuthorisedExaminer(aeDetails.getId());

        assertFalse(authorisedExaminerOverviewPageOne
                        .verifyUserAddedToAEDList(fakeTestSurname, trader.getFullName()),
                "Check to ensure user not added to AED list on AE page");

        SiteDetailsPage siteDetailsPage = authorisedExaminerOverviewPageOne
                .clickVTSLinkExpectingSiteDetailsPage(site.getName());
        DateTime nominationResponse = new DateTime();

        assertTrue(siteDetailsPage.isNominationResponseDateReflected(nominationResponse),
                "Check the date on the nomination message");

        siteDetailsPage.clickLogout();
        Login veLogin = createVE();
        HelpdeskUserResultsPage helpdeskUserResultsPage =
                HelpdeskUserSearchPage.navigateHereFromLoginPage(driver, veLogin)
                        .enterUsername(traderLogin.username).search();
        EventHistoryPage helpdeskUserProfilePage =
                helpdeskUserResultsPage.clickUserName(0).clickEventHistoryLink();

        assertNotEquals(helpdeskUserProfilePage.getEventsTopHeaderText(), "No events to show",
                "Check to ensure no events to show is not displayed");

    }

    @Test(groups = {"Regression", "VM-5039", "VM-2231", "Sprint 24"})
    public void testRemoveTradeRoleAtSiteLevel() {
        AeDetails aeDetails = aeService.createAe("AE_");
        String siteName = "VTS_";
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        siteName);
        Login aedLogin = new AedCreationApi()
                .createAed(Collections.singleton(aeDetails.getId()), Login.LOGIN_SCHEME_MANAGEMENT,
                        "AED_");
        Person testerLogin = createTesterAsPerson(Arrays.asList(1));
        SiteDetailsPage siteDetailsPage =
                SiteDetailsPage.navigateHereFromLoginPage(driver, aedLogin, site)
                        .clickAssignARoleLink().enterUsername(testerLogin.login.username).search()
                        .selectRoleAndSubmit(Role.TESTER)
                        .clickOnConfirmNominationExpectingSiteDetailsPage();
        siteDetailsPage.clickLogout();
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, testerLogin.login)
                        .clickViewNomination().clickAcceptNomination().clickBackToHomeLink();
        userDashboardPage.clickLogout();
        userDashboardPage.navigateHereFromLoginPage(driver, aedLogin).clickNominationAcceptedLink()
                .clickBackToHomeLink().clickOnSiteLink(site).clickRemoveRoleLink()
                .clickConfirmRoleRemoval();
        Assert.assertEquals(siteDetailsPage.getRoleRemovalSuccessNotification(),
                Notifications.getRoleRemovalMessage(testerLogin.getNamesAndSurname()));
        userDashboardPage.clickLogout();
        userDashboardPage.navigateHereFromLoginPage(driver, testerLogin.login)
                .clickRoleRemovalLink();
        NotificationsRoleRemovalPage notificationsRoleRemovalPage =
                new NotificationsRoleRemovalPage(driver);
        Assert.assertTrue(notificationsRoleRemovalPage.getRemovalNotificationMessage()
                        .contains("Your Tester role association has been removed from Test Site"),
                ("Tester role association has been removed from Site"));
        Assert.assertTrue(notificationsRoleRemovalPage.getRoleRemovedDateAndTimeMessage()
                .contains("Notification sent"), "Notification of role removal is displayed");
    }

    @Test(groups = {"Regression", "VM-5039"}) public void testRemoveTradeRoleAtOrganisationLevel() {
        AeDetails aeDetails = aeService.createAe("AE_");
        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_SCHEME_MANAGEMENT, false);
        Login aedLogin = Login.LOGIN_AED1;
        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, aedmLogin)
                        .clickAssignRole().enterUsername(Login.LOGIN_AED1.username).search()
                        .selectAeRole(Role.AED).clickAssignARoleButton().clickOnConfirmButton();
        authorisedExaminerOverviewPage.clickLogout();
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, aedLogin)
                        .clickViewAedNomination().clickAcceptNomination().clickBackToHomeLink();
        userDashboardPage.clickLogout();
        userDashboardPage.navigateHereFromLoginPage(driver, aedmLogin).clickNominationAcceptedLink()
                .clickBackToHomeLink().clickFirstAeLink().clickRemoveAedRole()
                .clickConfirmRoleRemoval();
        Assert.assertEquals(authorisedExaminerOverviewPage.getRoleRemovalNotification(),
                Notifications.ASSERTION_AED_ROLE_REMOVAL.assertion);
        userDashboardPage.clickLogout();
        userDashboardPage.navigateHereFromLoginPage(driver, aedLogin).clickRoleRemovalLink();
        NotificationsRoleRemovalPage notificationsRoleRemovalPage =
                new NotificationsRoleRemovalPage(driver);
        Assert.assertTrue(notificationsRoleRemovalPage.getRemovalNotificationMessage().contains(
                        "Your Authorised Examiner Delegate role association has been removed from Test Organisation"),
                "AED role association has been removed from Organisation");
        Assert.assertTrue(notificationsRoleRemovalPage.getRoleRemovedDateAndTimeMessage()
                .contains("Notification sent"), "Notification of role removal is displayed");
    }

    @Test(description = "buggy logic, raised as VM-9702",
            groups = {"VM-3489", "VM3426", "Sprint 25", "Regression"})
    public void testOutsideOpeningHoursNotification() {

        Days currentDay = Days.getCurrentDay();
        OpeningHours outsideOpeningHours = OpeningHours.outsideOpeningHours();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);
        AeDetails aeDetails = aeService.createAe("AE_testOutside");
        String siteName = "VTS_testOutside";
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        siteName);
        Login smgr = createSM(Arrays.asList(site.getId()), Login.LOGIN_AREA_OFFICE2, null);
        Person trader = createTesterAsPerson(Arrays.asList(site.getId()));

        Login traderLogin = trader.getLogin();
        ManageOpeningHoursPage manageOpeningHoursPage =
                ManageOpeningHoursPage.navigateHereFromLoginPage(driver, smgr, site);
        manageOpeningHoursPage.updateOpeningHours(outsideOpeningHours, currentDay);
        SiteDetailsPage siteDetailsPage = manageOpeningHoursPage.clickUpdateOpeningHours();
        assertThat("Closed Opening Hours",
                siteDetailsPage.isHoursCorrectForDay(currentDay, outsideOpeningHours), is(true));

        createMotTest(traderLogin, site, vehicle, 12345, MotTestApi.TestOutcome.PASSED);

        siteDetailsPage.clickLogout();
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, smgr);
        assertThat("SiteManager Receives Notification",
                userDashboardPage.numberOfNotificationsByTitle("Test outside opening hours") == 1,
                is(true));
    }

}
