package com.dvsa.mot.selenium.priv.frontend.openam.test;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.AuthService;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpDeskUserProfilePage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpdeskUserSearchPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.AuthorisationFailedPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import org.testng.annotations.Test;

public class OpenAMAccountLockoutTests extends BaseTest {

    AuthService authService = new AuthService();
    
    @Test (groups = {"Regression"})
    public void testOpenAMLockoutAfterInvalidLoginAttempts() {
        Login login = createTester();
        authService.forceUserLockout(login, 5);
        new LoginPage(driver).loginExpectingToBeLockedOut(driver, login);
    }

    @Test (groups = {"Regression"})
    public void testOpenAmAccountCanBeUnlocked() {
        Login login = createTester();
        authService.forceUserLockout(login, 5);

        HelpDeskUserProfilePage helpDeskUserProfilePage = HelpdeskUserSearchPage
            .navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
            .enterUsername(login.username).search().clickUserName(0);
        helpDeskUserProfilePage.resetAccountByPost().clickReclaimAccountButton().clickLogout();

        AuthorisationFailedPage authorisationFailedPage = new LoginPage(driver)
            .loginExpectingAuthorisationFailedPage(driver, login.username, login.password);
        authorisationFailedPage.checkAuthFailedTitle();
    }
}
