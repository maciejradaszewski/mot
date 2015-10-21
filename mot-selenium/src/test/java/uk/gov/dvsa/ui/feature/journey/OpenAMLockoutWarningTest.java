package uk.gov.dvsa.ui.feature.journey;

import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.helper.ConfigHelper;
import uk.gov.dvsa.helper.RandomDataGenerator;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.interfaces.DisplayMessage;
import uk.gov.dvsa.ui.pages.login.AuthenticationFailedPage;
import uk.gov.dvsa.ui.pages.login.LockOutWarningDasDisabledPage;
import uk.gov.dvsa.ui.pages.login.LockOutWarningDasEnabledPage;
import uk.gov.dvsa.ui.pages.login.LoginPage;

import java.io.IOException;

public class OpenAMLockoutWarningTest extends BaseTest {

    private User tester;
    private Site testSite;
    private AeDetails aeDetails;
    private int numberOfAttempts = 3;
    private String randomPassword = RandomDataGenerator.generatePassword(8);

    @BeforeMethod(alwaysRun = true)
    public void setUp() throws IOException {
        aeDetails = aeData.createAeWithDefaultValues();
        testSite = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        tester = userData.createTester(testSite.getId());
    }

    @Test(groups = {"Regression", "VM-12163"},
            description = "Test that validates the that user is redirected to authentication failed page after 4 incorrect password attempts")
    public void authenticationFailedFor4Times() throws IOException, InterruptedException {

        // Given that I am on the Login page
        LoginPage loginPage = pageNavigator.goToLoginPage().refreshPage();

        // When I am providing wrong password for 3 times I am being redirected to Authentication failed page
        DisplayMessage message = tryToLoginWithInvalidCredentials(numberOfAttempts, loginPage);

        // Then I am redirected to Lockout warning page
        message.isMessageDisplayed();
    }

    private DisplayMessage tryToLoginWithInvalidCredentials(int numberOfAttempts, LoginPage loginPage) throws IOException {
        for (int i = 0; i < numberOfAttempts; i++) {
            AuthenticationFailedPage authenticationFailedPage =
                    loginPage.loginWithGivenCredentials(AuthenticationFailedPage.class, tester.getUsername(), randomPassword);

            authenticationFailedPage.returnToLoginPage();
        }

        if (ConfigHelper.isOpenamDASEnabled()) {
            return loginPage.loginWithGivenCredentials(LockOutWarningDasEnabledPage.class, tester.getUsername(), randomPassword);
        } else {
            return loginPage.loginWithGivenCredentials(LockOutWarningDasDisabledPage.class, tester.getUsername(), randomPassword);
        }
    }
}