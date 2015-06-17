package com.dvsa.mot.selenium.priv.frontend.openam.test;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.openam.OpenAMClaimAccountSignInPage;
import org.testng.Assert;
import org.testng.annotations.Test;


public class OpenAMClaimAccountEndToEndTest extends BaseTest {

    @Test(groups = {"slice_A", "VM-2901"}) public void testMot2LogInWithNewPassword() {

        Login login = createTester(true);
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver).navigateToClaimAccountPage(driver, login);
        openAMClaimAccountSignInPage.submitEmailSuccessfully(
                Person.getUnique(Person.PERSON_1, "loginIntoOpenAMClaimAccount")).enterNewPassword(
                Text.TEXT_RESET_PASSWORD)
                .enterNewConfirmPassword(Text.TEXT_RESET_PASSWORD).clickOnSubmitButton()
                .setSecurityQuestionAndAnswersSuccessfully().clickOnSubmitButton()
                .clickSaveAndContinue().clickLogout();
        LoginPage loginPage = new LoginPage(driver);
        loginPage.submitLoginExpectingFailure(Login.LOGIN_INVALID_USERNAME_AND_PASSWORD.username,
                Login.LOGIN_INVALID_USERNAME_AND_PASSWORD.password);
        Assert.assertTrue(loginPage.getPageSource().contains("Authentication failed"));
    }
}
