package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.VehicleSearchPage;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class SingleVtsTesterTest extends BaseTest {

    @Test(groups = {"Regression", "VM-2950"})
    public void testSingleVtsTesterShouldNotHaveToSelectLocationAtTestStart() {

        UserDashboardPage dashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);
        dashboardPage.clickStartMotTest();
        VehicleSearchPage vehicleSearchPage = new VehicleSearchPage(driver);

        assertThat("Check current site header", vehicleSearchPage.isCurrentSiteDisplayedInHeader(),
                is(true));
    }

    @Test(groups = {"Regression", "VM-2950"})
    public void testSingleVtsTesterShouldNotSeeChangeSiteLink() {

        UserDashboardPage dashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);
        VehicleSearchPage vehicleSearchPage = dashboardPage.startMotTest();

        assertThat("Check if site link is displayed", vehicleSearchPage.isChangeSiteLinkDisplayed(),
                is(false));
    }

}
