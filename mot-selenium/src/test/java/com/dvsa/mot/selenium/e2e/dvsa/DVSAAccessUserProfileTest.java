package com.dvsa.mot.selenium.e2e.dvsa;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.*;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.framework.api.vehicle.Vm10519userCreationApi;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EventHistoryPage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpDeskUserProfilePage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpdeskUserResultsPage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpdeskUserSearchPage;
import com.dvsa.mot.selenium.priv.frontend.user.RecordDemoPageGroupA;
import com.dvsa.mot.selenium.priv.frontend.user.RecordDemoPageGroupB;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.pub.frontend.application.tester.pages.NotificationPage;
import org.apache.commons.lang3.RandomStringUtils;
import org.joda.time.DateTime;
import org.joda.time.format.DateTimeFormat;
import org.joda.time.format.DateTimeFormatter;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;

import java.util.ArrayList;
import java.util.Collections;

import static org.testng.Assert.assertEquals;
import static org.testng.Assert.assertFalse;

public class DVSAAccessUserProfileTest extends BaseTest {

    private AeService aeService = new AeService();
    private Login superVehicleExaminerUser = new Vm10519userCreationApi().createVm10519user();
    private Login vehicleExaminerUser = new VehicleExaminerUserCreationApi().createVehicleExaminerUser();

    @Test(groups = {"Regression", "VM-7647",
            "VM-10283"}, description = "Test that validates the Super Vehicle Examiner user roles can access user profiles")
    public void testProfileDetailsDisplayed() {
        Person personUser = Person.BOB_THOMAS;
        HelpDeskUserProfilePage helpDeskUserProfilePage = userSearch(personUser, superVehicleExaminerUser);

        assertEquals(helpDeskUserProfilePage.getDateOfBirth(), personUser.getDateOfBirth(),
                "Check that the date of birth is displayed");
        assertEquals(helpDeskUserProfilePage.getEmail(), personUser.getEmail(),
                "Check that email address is displayed");
        assertEquals(helpDeskUserProfilePage.getName(), personUser.getFullName(),
                "Check that the full name is displayed");
        assertEquals(helpDeskUserProfilePage.getUserName(),
                Assertion.ASSERTION_TESTER_USERNAME.assertion, "Check that username is displayed");
        assertEquals(helpDeskUserProfilePage.getLicenceNumber(),
                Assertion.ASSERTION_DRIVER_LICENCE.assertion,
                "Check that the the driver's licence is displayed for tester");
        assertEquals(helpDeskUserProfilePage.getTelephoneNumber(), personUser.getTelNo(),
                "Check that the telephone number is displayed");
        assertEquals(helpDeskUserProfilePage.getTesterAssociation(),
                Assertion.ASSERTION_TESTER_VTS.assertion,
                "check that the correct VTS is displayed for tester role");
        assertEquals(helpDeskUserProfilePage.getAddress(), personUser.getAddress(),
                "Check that the home address is displayed");
        assertFalse(helpDeskUserProfilePage.isPasswordResetDisplayed(),
                "Check that the password reset button is not displayed");
        assertFalse(helpDeskUserProfilePage.isUsernameResetDisplayed(),
                "Check that the username reset button is not displayed");
        qualificationStatusDisplayed(helpDeskUserProfilePage,
                Assertion.ASSERTION_QUALIFICATION_STATUS_GROUP_QUALIFIED,
                Assertion.ASSERTION_QUALIFICATION_STATUS_GROUP_QUALIFIED);
    }

    @Test(groups = {"Regression", "VM-10519", "VM-10520", "VM-10521"},
            description = "Test that validates the Super Vehicle Examiner user can asses the demo test")
    public void testDemoAssessment() {
        Person personUser = createTesterUser(TesterCreationApi.TesterStatus.DMTN);

        HelpDeskUserProfilePage helpDeskUserProfilePageDemoNeededA =
                userSearch(personUser, superVehicleExaminerUser);
        qualificationStatusDisplayed(helpDeskUserProfilePageDemoNeededA,
                Assertion.ASSERTION_QUALIFICATION_STATUS_GROUP_DEMO_TEST_NEEDED,
                Assertion.ASSERTION_QUALIFICATION_STATUS_GROUP_DEMO_TEST_NEEDED);


        RecordDemoPageGroupA recordDemoPageGroupA =
                helpDeskUserProfilePageDemoNeededA.clickRecordDemoLinkGroupA();
        HelpDeskUserProfilePage helpDeskUserProfilePage = recordDemoPageGroupA.clickConfirm();
        qualificationStatusDisplayed(helpDeskUserProfilePage,
                Assertion.ASSERTION_QUALIFICATION_STATUS_GROUP_QUALIFIED,
                Assertion.ASSERTION_QUALIFICATION_STATUS_GROUP_DEMO_TEST_NEEDED);
        HelpDeskUserProfilePage helpDeskUserProfilePageDemoNeededB =
                eventHistoryCheck("A", helpDeskUserProfilePage);

        RecordDemoPageGroupB recordDemoPageGroupB =
                helpDeskUserProfilePageDemoNeededB.clickRecordDemoLinkGroupB();
        HelpDeskUserProfilePage helpDeskUserProfilePageQualified =
                recordDemoPageGroupB.clickConfirm();
        qualificationStatusDisplayed(helpDeskUserProfilePageQualified,
                Assertion.ASSERTION_QUALIFICATION_STATUS_GROUP_QUALIFIED,
                Assertion.ASSERTION_QUALIFICATION_STATUS_GROUP_QUALIFIED);
        HelpDeskUserProfilePage newHelpDeskUserProfilePageQualified =
                eventHistoryCheck("B", helpDeskUserProfilePageQualified);

        newHelpDeskUserProfilePageQualified.clickLogout();

        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, personUser.getLogin());
        userNotificationCheck(userDashboardPage, "A");
        userNotificationCheck(userDashboardPage, "B");
    }

    @Test(groups = {"Regression", "VM-10519", "VM-10520", "VM-10521"},
            description = "Test that validates the Vehicle Examiner user CANNOT asses the demo test")
    public void testDemoAssessmentNoPermission() {
        Person personUser = createTesterUser(TesterCreationApi.TesterStatus.DMTN);

        HelpDeskUserProfilePage helpDeskUserProfilePageDemoNeededA =
                userSearch(personUser, vehicleExaminerUser);
        qualificationStatusDisplayed(helpDeskUserProfilePageDemoNeededA,
                Assertion.ASSERTION_QUALIFICATION_STATUS_GROUP_DEMO_TEST_NEEDED_NO_PERMISSION,
                Assertion.ASSERTION_QUALIFICATION_STATUS_GROUP_DEMO_TEST_NEEDED_NO_PERMISSION);
    }

    @Test(groups = {"Regression", "VM-10519", "VM-10520", "VM-10521"},
            description = "Test that validates the Super Vehicle Examiner user can cancel the demo test")
    public void cancelDemoTestAssessment() {
        Person personUser = createTesterUser(TesterCreationApi.TesterStatus.DMTN);
        HelpDeskUserProfilePage helpDeskUserProfilePageDemoNeededA =
                userSearch(personUser, superVehicleExaminerUser);

        RecordDemoPageGroupA recordDemoPageGroupA =
                helpDeskUserProfilePageDemoNeededA.clickRecordDemoLinkGroupA();
        HelpDeskUserProfilePage helpDeskUserProfilePageDemoNeededB =
                recordDemoPageGroupA.clickCancel();

        RecordDemoPageGroupB recordDemoPageGroupB =
                helpDeskUserProfilePageDemoNeededB.clickRecordDemoLinkGroupB();
        recordDemoPageGroupB.clickCancel();
    }

    private HelpDeskUserProfilePage userSearch(Person personUser, Login DVSAUser) {
        HelpdeskUserResultsPage helpdeskUserResultsPage =
                HelpdeskUserSearchPage.navigateHereFromLoginPage(driver, DVSAUser)
                        .enterLastName(personUser.getSurname()).search();
        HelpDeskUserProfilePage helpDeskUserProfilePage = helpdeskUserResultsPage.clickUserName(0);

        return helpDeskUserProfilePage;
    }

    private void qualificationStatusDisplayed(HelpDeskUserProfilePage helpDeskUserProfilePage,
            Assertion ASSERTION_QUALIFICATION_STATUS_GROUP_A,
            Assertion ASSERTION_QUALIFICATION_STATUS_GROUP_B) {
        assertEquals(helpDeskUserProfilePage.getQualificationStatusGroupA(),
                ASSERTION_QUALIFICATION_STATUS_GROUP_A.assertion,
                "Check that the correct Qualification Status for the Group A is displayed");

        assertEquals(helpDeskUserProfilePage.getQualificationStatusGroupB(),
                ASSERTION_QUALIFICATION_STATUS_GROUP_B.assertion,
                "Check that the correct Qualification Status for the Group B is displayed");
    }

    private UserDashboardPage userNotificationCheck(UserDashboardPage userDashboardPage,
            String group) {
        NotificationPage notificationPage = userDashboardPage
                .clickNotification(String.format("Passed Group %s demonstration test", group));

        assertEquals(notificationPage.getNotificationContent(), String.format(
                "You passed your demonstration test. You are now qualified to test Group %s vehicles.",
                group), "Checks that notification message is correct");

        return notificationPage.clickHome();
    }

    private HelpDeskUserProfilePage eventHistoryCheck(String group,
            HelpDeskUserProfilePage helpDeskUserProfilePage) {
        EventHistoryPage eventHistoryPage = helpDeskUserProfilePage.clickEventHistoryLink();

        DateTime currentDate = new DateTime();
        DateTimeFormatter eventDateFormat = DateTimeFormat.forPattern("d MMM yyyy, HH:mma");
        DateTime eventDateToParse = eventDateFormat.parseDateTime(eventHistoryPage.getEventDate());
        String eventDate =
                eventDateToParse.toString(DateTimeFormat.forPattern("d MMM yyyy")).toString();

        String expectedDate = currentDate.toString(DateTimeFormat.forPattern("d MMM yyyy"));

        assertEquals(eventHistoryPage.getEventType(),
                String.format("Group %s Tester Qualification", group), String.format(
                        "Check to ensure Qualification Status change event type for Group %s is displayed",
                        group));

        assertEquals(eventDate, expectedDate, String.format(
                "Check to ensure Qualification Status change event date for Group %s is displayed",
                group));

        String description = String.format(
                "Qualified to test Group %s vehicles following a demonstration test. Recorded by",
                group);

        assertThat(String.format(
                        "Check to ensure Qualification Status change event description for Group %s is displayed",
                        group), eventHistoryPage.getDescription(), containsString(description));
        eventHistoryPage.clickGoBackLink();

        return new HelpDeskUserProfilePage(driver);
    }

    private Person createTesterUser(TesterCreationApi.TesterStatus status) {
        ArrayList vts = new ArrayList();
        String aeName = RandomStringUtils.randomAlphabetic(6);
        String vtsName = RandomStringUtils.randomAlphabetic(6);
        AeDetails aeDetails = aeService.createAe(aeName);

        Login aeraOfficer1User =
                new AreaOffice1UserCreationApi().createAreaOffice1User();
        int vtsId = createVTS(aeDetails.getId(), TestGroup.group1, aeraOfficer1User, vtsName);
        vts.add(vtsId);

        Person personUser = new TesterCreationApi()
                .createTesterAsPerson(Collections.singletonList(vtsId), TestGroup.group1,
                        status, aeraOfficer1User, vtsName, false, false);

        return personUser;
    }
}
