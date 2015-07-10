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
}
