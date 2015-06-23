package com.dvsa.mot.selenium.priv.frontend.openam.test;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.openam.OpenAMClaimAccountSecurityQuestionsPage;
import com.dvsa.mot.selenium.priv.frontend.openam.OpenAMClaimAccountSignInPage;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;


public class OpenAMClaimAccountSecurityQuestionsTest extends BaseTest {


    @Test(groups = {"Regression", "VM-4705"})
    public void testOpenAMClaimAccountWithSecurityQuestionsWithNoMemorableAnswers() {
        loginIntoOpenAMClaimAccount();
        OpenAMClaimAccountSecurityQuestionsPage openAMClaimAccountSecurityQuestionsPage =
                new OpenAMClaimAccountSecurityQuestionsPage(driver);
        openAMClaimAccountSecurityQuestionsPage.setFirstSecurityQuestionAndAnswer()
                .setSecondSecurityQuestionAndAnswer().clickOnSubmitButton();

        assertThat(ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
    }

    public void loginIntoOpenAMClaimAccount() {

        Login login = createTester(true);
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver).navigateToClaimAccountPage(driver, login);
        openAMClaimAccountSignInPage.submitEmailSuccessfully(
                Person.getUnique(Person.PERSON_1, "loginIntoOpenAMClaimAccount"))
                .submitPasswordSuccessfully(Text.TEXT_PASSWORD_2).clickOnSubmitButton();
    }
}
