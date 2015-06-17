package com.dvsa.mot.selenium.priv.frontend.openam.test;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.openam.ChangePasswordPage;

import com.dvsa.mot.selenium.priv.frontend.openam.OpenAMClaimAccountMotTestPinPage;
import com.dvsa.mot.selenium.priv.frontend.openam.OpenAMClaimAccountSignInPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.testng.annotations.Test;

import java.util.Collections;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class ResetPasswordTests extends BaseTest{

    @Test (groups = {"slice_A"})
    public void passwordResetOnLogin() {

        Login login = createTester(Collections.singleton(Site.POPULAR_GARAGES.getId()), TestGroup.ALL,
            false, true);

        ChangePasswordPage changePasswordPage = new LoginPage(driver)
            .loginExpectingChangePasswordPage(driver, login);

        UserDashboardPage userDashboardPage =
            changePasswordPage.enterNewPasswordAndSubmitChangesSuccessfully();

        assertThat("Ensure the user has made it to the dashboard page",
            userDashboardPage.isViewAllForSpecialNoticesLinkClickable(), is(true));
    }

    @Test(groups = {"slice_A"})
    public void passwordResetAfterClaimingAccount() {
        Login login = createTester(Collections.singleton(Site.POPULAR_GARAGES.getId()), TestGroup.ALL,
            true, true);
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage = new LoginPage(driver)
            .navigateToClaimAccountPage(driver, login);

        ChangePasswordPage changePasswordPage = openAMClaimAccountSignInPage
            .submitPasswordSuccessfully(RandomDataGenerator.generatePassword(8))
            .clickOnSubmitButton().setSecurityQuestionAndAnswersSuccessfully()
            .clickOnSubmitButton().clickSaveAndContinueExpectingChangePasswordPage();

        changePasswordPage.enterNewPasswordAndSubmitChangesSuccessfully();
    }

    @Test(groups = {"slice_A"})
    public void changePasswordValidationChecks(){
        Login login = createTester(Collections.singleton(Site.POPULAR_GARAGES.getId()), TestGroup.ALL,
            false, true);

        ChangePasswordPage changePasswordPage = new LoginPage(driver)
            .loginExpectingChangePasswordPage(driver, login);

        changePasswordPage.enterNewPasswordAndSubmitExpectingFailure()
            .isValidationMessageDisplayed();
    }
}
