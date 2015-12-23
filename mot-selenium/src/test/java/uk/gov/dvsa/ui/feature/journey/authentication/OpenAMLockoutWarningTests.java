package uk.gov.dvsa.ui.feature.journey.authentication;

import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.helper.RandomDataGenerator;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.interfaces.DisplayMessage;
import uk.gov.dvsa.ui.pages.login.AuthenticationFailedPage;
import uk.gov.dvsa.ui.pages.login.LockOutWarningPage;
import uk.gov.dvsa.ui.pages.login.LoginPage;

import java.io.IOException;

public class OpenAMLockoutWarningTests extends BaseTest {

    private int numberOfAttempts = 4;

    @Test(groups = {"Regression", "VM-12163"},
            description = "Test that validates the that user is redirected to authentication failed page after 4 incorrect password attempts")
    public void authenticationFailedFor4Times() throws IOException, InterruptedException {

        // Given that I am on the Login page
        LoginPage loginPage = pageNavigator.goToLoginPage();

        // When I provide incorrect password for 4 times
        DisplayMessage message = tryToLoginWithInvalidCredentials(numberOfAttempts, loginPage);

        // Then I am redirected to Lockout warning page
        message.isMessageDisplayed();
    }

    private DisplayMessage tryToLoginWithInvalidCredentials(int numberOfAttempts, LoginPage loginPage) throws IOException {
        String randomPassword = RandomDataGenerator.generatePassword(8);
        final User tester = userData.createTester(siteData.createSite().getId());

        for (int i = 1; i < numberOfAttempts; i++) {
            AuthenticationFailedPage authenticationFailedPage =
                    loginPage.login(
                            AuthenticationFailedPage.class, tester.getUsername(), randomPassword);

            authenticationFailedPage.returnToLoginPage();
        }

        return loginPage.login(LockOutWarningPage.class, tester.getUsername(), randomPassword);
    }
}