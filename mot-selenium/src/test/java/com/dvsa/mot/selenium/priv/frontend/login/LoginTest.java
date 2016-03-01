package com.dvsa.mot.selenium.priv.frontend.login;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.testng.Assert;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class LoginTest extends BaseTest {

    @Test(groups = {"Regression", "VM-14",
            "short-login"}, description = "Registered user in Tester role logs in by pressing Enter key in Password field on Login screen.")
    public void testLoginSubmitWithEnterKeyFromPasswordField() {

        Login login = createTester();

        UserDashboardPage dashboardPage =
                new LoginPage(driver).loginAsUserSubmitWithEnterKeyInPasswordField(login);
        dashboardPage.verifyOnDashBoard();
        LoginPage loginPage = dashboardPage.clickLogout();

        assertThat("Assert user is logged out", loginPage.isUserLoggedIn(), is(false));
    }

    @Test(groups = "Regression", description = "Registered user in Tester role logs in by pressing Enter key in Username field on Login screen.")
    public void testLoginSubmitWithEnterKeyFromUsernameField() {

        Login login = createTester();

        UserDashboardPage dashboardPage =
                new LoginPage(driver).loginAsUserSubmitWithEnterKeyInUsernameField(login);
        dashboardPage.verifyOnDashBoard();
        LoginPage loginPage = dashboardPage.clickLogout();

        assertThat("Assert user is logged out", loginPage.isUserLoggedIn(), is(false));
    }

    @Test(groups = {"VM-3893", "Regression", "W-Sprint3"},
            description = "Check the links to manuals displayed on the page footer.")
    public void testFootManualsAndGuidesLinks() {

        UserDashboardPage dashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);

        Assert.assertEquals("Manual for class 1 and 2 vehicles",
                dashboardPage.getManualNameForClass1And2());
        Assert.assertEquals("Manual for class 3, 4, 5, and 7 vehicles",
                dashboardPage.getManualNameForClass345And7());
        Assert.assertEquals("MOT testing guide",
                dashboardPage.getManualTestingGuideName());
        Assert.assertEquals("Checklist for class 1 and 2 vehicles (VT29M)",
                dashboardPage.getResourcesNameChecklistForClass1And2());
        Assert.assertEquals("Checklist for class 3, 4, 5, and 7 vehicles (VT29)",
                dashboardPage.getResourcesNameChecklistForClass345And7());
        Assert.assertEquals("In service exhaust emission standards for road vehicles: 18th edition",
                dashboardPage.getResourcesNameEmissionStandards());
        Assert.assertEquals("Special notices",
                dashboardPage.getResourcesNameSpecialNotices());
    }
}
