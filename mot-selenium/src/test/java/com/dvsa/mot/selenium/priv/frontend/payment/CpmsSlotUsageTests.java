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
import org.testng.Assert;
import org.testng.annotations.Test;

import java.util.Collections;

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

    @Test(groups = {"slice_A", "SPMS-119"}) public void slotUsageReportTest() {

        Login aedmLogin = createMotTestsAndReturnAedmLogin();
        OrganisationSlotsUsagePage organisationSlotsUsagePage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, aedmLogin)
                        .clickSlotUsageLink();

        Assert.assertTrue(organisationSlotsUsagePage.isNumberOfSlotsUsedPresent(),
                "Verifying Number of slots used displayed");
        Assert.assertTrue(organisationSlotsUsagePage.isSlotUsageTablePresent(),
                "Verifying Slot usage table present");
        Assert.assertTrue(organisationSlotsUsagePage.isDownloadPdfReportLinkPresent(),
                "Verifying PDF Link");
        Assert.assertTrue(organisationSlotsUsagePage.isDownloadCsvReportLinkPresent(),
                "Verifying CSV Link");

        OrganisationSlotsUsagePage slotsUsedTodayPage =
                organisationSlotsUsagePage.filterSlotsUsedToday();

        Assert.assertTrue(slotsUsedTodayPage.isNumberOfSlotsUsedPresent(),
                "Verifying Number of slots used Today displayed");
        Assert.assertTrue(slotsUsedTodayPage.isSlotUsageTablePresent(),
                "Verifying Slot usage table present");
        Assert.assertTrue(slotsUsedTodayPage.isDownloadPdfReportLinkPresent(),
                "Verifying PDF Link");
        Assert.assertTrue(slotsUsedTodayPage.isDownloadCsvReportLinkPresent(),
                "Verifying CSV Link");

        OrganisationSlotsUsagePage slotsUsedLast7DaysPage =
                organisationSlotsUsagePage.filterSlotsUsedLast7days();

        Assert.assertTrue(slotsUsedLast7DaysPage.isNumberOfSlotsUsedPresent(),
                "Verifying Number of slots used Last 7 days displayed");
        Assert.assertTrue(slotsUsedLast7DaysPage.isSlotUsageTablePresent(),
                "Verifying Slot usage table present");
        Assert.assertTrue(slotsUsedLast7DaysPage.isDownloadPdfReportLinkPresent(),
                "Verifying PDF Link");
        Assert.assertTrue(slotsUsedLast7DaysPage.isDownloadCsvReportLinkPresent(),
                "Verifying CSV Link");

        OrganisationSlotsUsagePage slotsUsedLast30DaysPage =
                organisationSlotsUsagePage.filterSlotsUsedLast30days();

        Assert.assertTrue(slotsUsedLast30DaysPage.isNumberOfSlotsUsedPresent(),
                "Verifying Number of slots used Last 30 days displayed");
        Assert.assertTrue(slotsUsedLast30DaysPage.isSlotUsageTablePresent(),
                "Verifying Slot usage table present");
        Assert.assertTrue(slotsUsedLast30DaysPage.isDownloadPdfReportLinkPresent(),
                "Verifying PDF Link");
        Assert.assertTrue(slotsUsedLast30DaysPage.isDownloadCsvReportLinkPresent(),
                "Verifying CSV Link");

        OrganisationSlotsUsagePage slotsUsedLastYearPage =
                organisationSlotsUsagePage.filterSlotsUsedLastYear();

        Assert.assertTrue(slotsUsedLastYearPage.isNumberOfSlotsUsedPresent(),
                "Verifying Number of slots used Last year displayed");
        Assert.assertTrue(slotsUsedLastYearPage.isSlotUsageTablePresent(),
                "Verifying Slot usage table present");
        Assert.assertTrue(slotsUsedLastYearPage.isDownloadPdfReportLinkPresent(),
                "Verifying PDF Link");
        Assert.assertTrue(slotsUsedLastYearPage.isDownloadCsvReportLinkPresent(),
                "Verifying CSV Link");
    }

    @Test(groups = {"slice_A", "SPMS-119"}) public void vtsSlotUsageReportTest() {

        Login aedmLogin = createMotTestsAndReturnAedmLogin();
        VehicleTestStationSlotUsagePage vehicleTestStationSlotUsagePage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, aedmLogin)
                        .clickSlotUsageLink().clickVtsNumber();

        Assert.assertTrue(vehicleTestStationSlotUsagePage.isSlotUsageTableDisplayed(),
                "Verifying Slot Usage Table");
        Assert.assertTrue(vehicleTestStationSlotUsagePage.isDownloadPdfReportLinkDisplayed(),
                "Verifying PDF Link");
        Assert.assertTrue(vehicleTestStationSlotUsagePage.isDownloadCsvReportLinkDisplayed(),
                "Verifying CSV Link");
    }

}
