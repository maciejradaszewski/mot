package com.dvsa.mot.selenium.priv.frontend.organisation.management;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.datasource.enums.Days;
import com.dvsa.mot.selenium.datasource.enums.Role;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.GoToTheUrl;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.errors.UnauthorisedError;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.ManageOpeningHoursPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AssignARoleConfirmationPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages.ConfigureBrakeTestDefaultsPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages.BrakeTestConfigurationPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import org.testng.Assert;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import java.util.HashMap;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;


public class VehicleTestingStationOverviewTest extends BaseTest {


    @Test(groups = {"VM-2329", "VM-2227", "VM-2545", "VM-2297", "VM-2552", "VM-3240", "Sprint-23",
            "LA-2", "current"}) public void testTesterRoleAssociationWithTheSite() {

        Login login1 = createTester();
        SiteDetailsPage siteDetailsPage = AssignARoleConfirmationPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_AEDM, Site.JOHNS_MOTORCYCLE_GARAGE,
                        login1.username, Role.TESTER)
                .clickOnConfirmNominationExpectingSiteDetailsPage();

        Assert.assertTrue(siteDetailsPage.isVtsContactDetailsDisplayed(),
                "the vts contact details is not displayed");
        siteDetailsPage.clickLogout();
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login1);
        assertThat(userDashboardPage.isUnreadNotifications(), is(true));
        userDashboardPage.clickNotification("Tester nomination").clickAcceptNomination()
                .backToHomePage().startMotTestAsManyVtsTesterWithoutVtsChosen()
                .selectVtsByName(Site.JOHNS_MOTORCYCLE_GARAGE.getName()).confirmSelected();

    }

    @DataProvider(name = "rolesWhoCanMaintainTheBrakeTestPreferences")
    public Object[][] rolesWhoCanMaintainTheBrakeTestPreferences() {
        return new Object[][] {{Login.LOGIN_AEDM}, {Login.LOGIN_AED1},
                {Login.LOGIN_SITE_MANAGER_AT_V123539}};
    }

    @Test(groups = {"VM-3270", "Sprint 25", "slice_A"},
            dataProvider = "rolesWhoCanMaintainTheBrakeTestPreferences",
            description = "Test that the brake test configuration appear in the VTS details page, and ensure the info displayed is correct")
    public void testRolesWhoCanViewAndChangeSiteDefaultBrakeConfiguration(Login login) {
        SiteDetailsPage siteDetailsPage = SiteDetailsPage
                .navigateHereFromLoginPage(driver, login, Site.JOHNS_MOTORCYCLE_GARAGE);
        ConfigureBrakeTestDefaultsPage configureBrakeTestDefaultsPage =
                new ConfigureBrakeTestDefaultsPage(driver);
        Assert.assertTrue(
                configureBrakeTestDefaultsPage.isSelectedSiteDisplaysTheCorrectVehicleClass(),
                "Selected Site Vehicle Test Class Doesn't Match");

        String BrakeTestType = siteDetailsPage.clickOnChangeDefaults().
                selectABrakeTestType(BrakeTestConstants.BrakeTestType.Roller).
                getDefaultParkingBrakeTestType();
        configureBrakeTestDefaultsPage.clickSaveButton();
        Assert.assertTrue(siteDetailsPage.isBrakeTestDefaultsDisplayedCorrectly(BrakeTestType));
    }

    @Test(groups = {"VM-3558", "Sprint 25", "slice_A"},
            description = "Test that the default brake test configuration appears for a tester doing an mot test,and ensure the info displayed is correct")
    public void testSiteHasNoPreferenceAndAssignTheDefaultBrakingConfiguration() {
        String serviceBrakeTestType = SiteDetailsPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_SITE_MANAGER, Site.POPULAR_GARAGES)
                .clickOnChangeDefaults().
                        selectABrakeTestType(BrakeTestConstants.BrakeTestType.ClassB).
                        getPlateDefaultServiceBrakeTestClass3AndAbove();
        ConfigureBrakeTestDefaultsPage configureBrakeTestDefaultsPage =
                new ConfigureBrakeTestDefaultsPage(driver);
        String parkingBrakeTestType =
                configureBrakeTestDefaultsPage.getPlateDefaultParkingBrakeTestClass3AndAbove();
        configureBrakeTestDefaultsPage.clickSaveButton();
        SiteDetailsPage siteDetailsPage = new SiteDetailsPage((driver));
        assertThat("Sites Default Brake Test", siteDetailsPage
                .isBrakeTestDefaultsForClassBDisplayedCorrectly(serviceBrakeTestType,
                        parkingBrakeTestType), is(true));
        configureBrakeTestDefaultsPage.clickLogout();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        MotTestPage motTestPage =
                MotTestPage.navigateHereFromLoginPage(driver, createTester(), vehicle);
        motTestPage.addBrakeTest();
        BrakeTestConfigurationPage brakeTestConfigurationPage =
                new BrakeTestConfigurationPage(driver);
        assertThat("Default Brake Tests", brakeTestConfigurationPage
                .isSelectedBrakeTestDefaultsForClassBDisplayedCorrectly(serviceBrakeTestType,
                        parkingBrakeTestType), is(true));
        brakeTestConfigurationPage.cancel()
                .addMotTest("12000", BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.allPass(), null, null, null, null).createCertificate()
                .clickFinishPrint(Text.TEXT_PASSCODE).clickDoneButton();
    }

    private OpeningHours testDataForOpeningHoursTest(Days day) {
        HashMap<Days, OpeningHours> openingHours = new HashMap<Days, OpeningHours>();
        openingHours.put(Days.MONDAY, OpeningHours.STANDARD_WEEKDAY_HOURS);
        openingHours.put(Days.TUESDAY, OpeningHours.STANDARD_WEEKDAY_HOURS);
        openingHours.put(Days.WEDNESDAY, OpeningHours.STANDARD_WEEKDAY_HOURS);
        openingHours.put(Days.THURSDAY, OpeningHours.STANDARD_WEEKDAY_HOURS);
        openingHours.put(Days.FRIDAY, OpeningHours.STANDARD_WEEKDAY_HOURS);
        openingHours.put(Days.SATURDAY, OpeningHours.OPEN_ONE_TO_SIX);
        openingHours.put(Days.SUNDAY, OpeningHours.VTS_IS_CLOSED);
        return openingHours.get(day);
    }

    @Test(groups = {"VM-3426", "VM-2865", "Sprint 25", "slice_A"})
    public void testChangingOpeningHours() {
        ManageOpeningHoursPage manageOpeningHoursPage = ManageOpeningHoursPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_AEDM, Site.JOHNS_MOTORCYCLE_GARAGE);
        for (Days days : Days.values()) {
            manageOpeningHoursPage.updateOpeningHours(testDataForOpeningHoursTest(days), days);
        }
        SiteDetailsPage siteDetailsPage = manageOpeningHoursPage.clickUpdateOpeningHours();

        for (Days days : Days.values()) {
            assertThat("Day is present", siteDetailsPage.isDayPresentInOpeningHours(days),
                    is(true));
            assertThat("Opening hours is correct",
                    siteDetailsPage.isHoursCorrectForDay(days, testDataForOpeningHoursTest(days)),
                    is(true));
        }
    }

    @Test(groups = {"VM_3426", "slice_A"}) public void testOpeningHoursValidation() {

        int aeId = createAE("AE_");
        String siteName = "VTS_";
        Site site = new VtsCreationApi()
                .createVtsSite(aeId, TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, siteName);
        Login responseData = createAEDM(aeId, Login.LOGIN_AREA_OFFICE2, false);
        Login aedmLogin = new Login(responseData.username, responseData.password);

        ManageOpeningHoursPage manageOpeningHoursPage =
                ManageOpeningHoursPage.navigateHereFromLoginPage(driver, aedmLogin, site);
        manageOpeningHoursPage
                .updateOpeningHours(OpeningHours.INVALID_OPENING_HOURS, Days.getCurrentDay())
                .clickUpdateOpeningHoursExpectingError();
        assertThat("Opening Hours Validation Message",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
    }

    @DataProvider(name = "rolesWhoCanViewInProgressTestsButCannotAbortItAtAnUsersVts")
    public Object[][] rolesWhoCanViewInProgressTestsButCannotAbortItAtAnUsersVts() {
        return new Object[][] {{Login.LOGIN_AEDM_2}, {Login.LOGIN_AED_3},};
    }

    @Test(groups = {"VM-4343", "slice_A", "Team-X"},
            dataProvider = "rolesWhoCanViewInProgressTestsButCannotAbortItAtAnUsersVts")
    public void testRolesWhoCanViewInProgressTestsButCannotAbortItAtAnUsersVts(Login login) {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        MotTestPage motTestPage =
                MotTestPage.navigateHereFromLoginPage(driver, Login.LOGIN_VTS_TESTER_1, vehicle);
        String motId = motTestPage.getMotTestId();
        motTestPage.clickLogout();

        SiteDetailsPage siteDetailsPage =
                SiteDetailsPage.navigateHereFromLoginPage(driver, login, Site.VENTURE_COMPOUND);
        try {
            siteDetailsPage.clickOnActiveMotTestLink(motId);
        } catch (UnauthorisedError e) {
            Assert.assertTrue(siteDetailsPage.isUnauthorisedErrorDisplayed());
        }
    }

    @Test(groups = {"VM-4503", "slice_A"})
    public void testAnotherTesterWhoCantViewAbortMotTestPageOfAnActiveTester() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        String testNumber =
                MotTestPage.navigateHereFromLoginPage(driver, login.LOGIN_VTS_TESTER_1, vehicle)
                        .getMotTestId();
        MotTestPage motTestPage = new MotTestPage(driver);
        motTestPage.clickLogout();
        UserDashboardPage.navigateHereFromLoginPage(driver, login.LOGIN_TESTER1);
        try {
            GoToTheUrl.goToVtsAbortTestPage(driver, testNumber);
        } catch (UnauthorisedError e) {
            Assert.assertTrue(motTestPage.isUnauthorisedErrorDisplayed());
        }
    }

    @Test(groups = {"VM-4503", "slice_A"})
    public void testAEDMCannotAbortMotTestPageOfAnActiveTester() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        String testNumber =
                MotTestPage.navigateHereFromLoginPage(driver, login.LOGIN_VTS_TESTER_1, vehicle)
                        .getMotTestId();
        MotTestPage motTestPage = new MotTestPage(driver);
        motTestPage.clickLogout();
        UserDashboardPage.navigateHereFromLoginPage(driver, login.LOGIN_AEDM);
        try {
            GoToTheUrl.goToVtsAbortTestPage(driver, testNumber);
        } catch (UnauthorisedError e) {
            Assert.assertTrue(motTestPage.isUnauthorisedErrorDisplayed());
        }
    }

}
