package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.MotTestApi;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.OrganisationSlotsUsagePage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.VehicleTestStationSlotUsagePage;
import org.joda.time.DateTime;
import org.testng.annotations.Test;

import java.util.Collections;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.Matchers.is;

public class CpmsSlotUsageTests extends BaseTest {

    private Login createMotTestsAndReturnAedmLogin() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("slotUsageJourney");
        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        "slotUsage");
        Login login = createTester(Collections.singleton(site.getId()));
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);

        createMotTest(login, site, vehicle, 15000, MotTestApi.TestOutcome.PASSED, DateTime.now());
        createMotTest(login, site, vehicle, 19000, MotTestApi.TestOutcome.PASSED,
                DateTime.now().minusDays(5));
        createMotTest(login, site, vehicle, 19000, MotTestApi.TestOutcome.PASSED,
                DateTime.now().minusDays(10));
        return aedmLogin;
    }

    @Test(groups = {"Regression", "SPMS-119"}) public void slotUsageReportTest() {

        Login aedmLogin = createMotTestsAndReturnAedmLogin();
        OrganisationSlotsUsagePage organisationSlotsUsagePage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, aedmLogin)
                        .clickSlotUsageLink();

        assertThat("Verifying Number of slots used",
                organisationSlotsUsagePage.getNumberOfSlotsUsed(),
                containsString(" used in the last 7 days"));
        assertThat("Verifying Slot usage table displayed",
                organisationSlotsUsagePage.isSlotUsageTableDisplayed(), is(true));
        assertThat("Verifying Download file options displayed",
                organisationSlotsUsagePage.isDownloadFileOptionsDisplayed(), is(true));

        OrganisationSlotsUsagePage slotsUsedTodayPage =
                organisationSlotsUsagePage.filterSlotsUsedToday();
        assertThat("Verifying Number of slots used - Today",
                slotsUsedTodayPage.getNumberOfSlotsUsed(), is("1 slot used today"));
        assertThat("Verifying Slot usage table displayed",
                slotsUsedTodayPage.isSlotUsageTableDisplayed(), is(true));
        assertThat("Verifying Download file options displayed",
                slotsUsedTodayPage.isDownloadFileOptionsDisplayed(), is(true));

        OrganisationSlotsUsagePage slotsUsedLast7DaysPage =
                organisationSlotsUsagePage.filterSlotsUsedLast7days();
        assertThat("Verifying Number of slots used - 7days",
                slotsUsedLast7DaysPage.getNumberOfSlotsUsed(),
                containsString(" used in the last 7 days"));
        assertThat("Verifying Slot usage table displayed",
                slotsUsedLast7DaysPage.isSlotUsageTableDisplayed(), is(true));
        assertThat("Verifying Download file options displayed",
                slotsUsedLast7DaysPage.isDownloadFileOptionsDisplayed(), is(true));

        OrganisationSlotsUsagePage slotsUsedLast30DaysPage =
                organisationSlotsUsagePage.filterSlotsUsedLast30days();
        assertThat("Verifying Number of slots used - 30days",
                slotsUsedLast30DaysPage.getNumberOfSlotsUsed(),
                containsString(" used in the last 30 days"));
        assertThat("Verifying Slot usage table displayed",
                slotsUsedLast30DaysPage.isSlotUsageTableDisplayed(), is(true));
        assertThat("Verifying Download file options displayed",
                slotsUsedLast30DaysPage.isDownloadFileOptionsDisplayed(), is(true));

        OrganisationSlotsUsagePage slotsUsedLastYearPage =
                organisationSlotsUsagePage.filterSlotsUsedLastYear();
        assertThat("Verifying Number of slots used - Lastyear",
                slotsUsedLastYearPage.getNumberOfSlotsUsed(),
                containsString(" used in the last year"));
        assertThat("Verifying Slot usage table displayed",
                slotsUsedLastYearPage.isSlotUsageTableDisplayed(), is(true));
        assertThat("Verifying Download file options displayed",
                slotsUsedLastYearPage.isDownloadFileOptionsDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "SPMS-119"}) public void vtsSlotUsageReportTest() {

        Login aedmLogin = createMotTestsAndReturnAedmLogin();
        VehicleTestStationSlotUsagePage vehicleTestStationSlotUsagePage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, aedmLogin)
                        .clickSlotUsageLink().clickVtsNumber();

        assertThat("Verifying Slot Usage Table displayed",
                vehicleTestStationSlotUsagePage.isSlotUsageTableDisplayed(), is(true));
        assertThat("Verifying Download File options displayed",
                vehicleTestStationSlotUsagePage.isDownloadFileOptionsDisplayed(), is(true));
    }

}
