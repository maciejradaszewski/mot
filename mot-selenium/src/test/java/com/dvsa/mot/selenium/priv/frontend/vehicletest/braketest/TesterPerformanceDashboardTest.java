package com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.MotTestApi;
import com.dvsa.mot.selenium.pub.frontend.application.tester.pages.PerformanceDashboardPage;
import org.testng.Assert;
import org.testng.annotations.Test;

public class TesterPerformanceDashboardTest extends BaseTest {

    @Test(groups = {"slice_A", "Sprint 24A", "VM-2266", "MOT Testing"},
            description = "A tester that conducts a MOT test can able to see the passed tests on his performance dashboard")
    public void testTesterUserStatsForTestPassed() {
        Login tester = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);
        createMotTest(tester, Site.POPULAR_GARAGES, vehicle, 12345, MotTestApi.TestOutcome.PASSED);

        PerformanceDashboardPage performanceDashboardPage =
                PerformanceDashboardPage.navigateHereFromLoginPage(driver, tester);
        Assert.assertTrue(performanceDashboardPage.getNumberOfTestsPassed() == 1);
    }

    @Test(groups = {"slice_A", "Sprint 24A", "VM-2266", "MOT Testing"},
            description = "A tester that conducts a MOT test can able to see the failed tests on his performance dashboard")
    public void testTesterUserStatsForTestFailed() {
        Login tester = createTester();
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BMW_ALPINA_28);
        createMotTest(tester, Site.POPULAR_GARAGES, vehicle, 12345, MotTestApi.TestOutcome.FAILED);

        PerformanceDashboardPage performanceDashboardPage =
                PerformanceDashboardPage.navigateHereFromLoginPage(driver, tester);
        Assert.assertTrue(performanceDashboardPage.getNumberOfTestsFailed() == 1);
        Assert.assertTrue(
                performanceDashboardPage.getCurrentMonthFailRate().equalsIgnoreCase("100.00%"));
    }
}
