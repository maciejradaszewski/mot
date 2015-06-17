package com.dvsa.mot.selenium.priv.frontend.login;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.pub.frontend.application.tester.pages.ManualsPage;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class LoginTest extends BaseTest {

    @Test(groups = {"slice_A", "VM-14",
            "short-login"}, description = "Registered user in Tester role logs in by pressing Enter key in Password field on Login screen.")
    public void testLoginSubmitWithEnterKeyFromPasswordField() {

        Login login = createTester();

        UserDashboardPage dashboardPage =
                new LoginPage(driver).loginAsUserSubmitWithEnterKeyInPasswordField(login);
        dashboardPage.verifyOnDashBoard();
        LoginPage loginPage = dashboardPage.clickLogout();

        assertThat("Assert user is logged out", loginPage.isUserLoggedIn(), is(false));
    }

    @Test(groups = "slice_A", description = "Registered user in Tester role logs in by pressing Enter key in Username field on Login screen.")
    public void testLoginSubmitWithEnterKeyFromUsernameField() {

        Login login = createTester();

        UserDashboardPage dashboardPage =
                new LoginPage(driver).loginAsUserSubmitWithEnterKeyInUsernameField(login);
        dashboardPage.verifyOnDashBoard();
        LoginPage loginPage = dashboardPage.clickLogout();

        assertThat("Assert user is logged out", loginPage.isUserLoggedIn(), is(false));
    }

    @Test(groups = {"VM-3893", "slice_A", "W-Sprint3"},
            description = "Check the links to manuals displayed on the page footer.")
    public void testFootManualsAndGuidesLinks() {

        UserDashboardPage dashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);
        ManualsPage manualsPage = dashboardPage.clickFooterManualAndGuidesLink();

        assertThat("Testing guides link not present in Manuals page",
                manualsPage.isTestingGuidesLinkPresent(), is(true));
        assertThat("Inspection manual Classes 1 & 2 link not present in Manuals page",
                manualsPage.isInspectionManualClasses1And2LinkPresent(), is(true));
        assertThat("Inspection manual Classes 3 to 7 link not present in Manuals page",
                manualsPage.isInspectionManualClasses3To7LinkPresent(), is(true));
        assertThat("Emissions book link not present in Manuals page",
                manualsPage.isEmissionsBookLinkPresent(), is(true));
    }
}
