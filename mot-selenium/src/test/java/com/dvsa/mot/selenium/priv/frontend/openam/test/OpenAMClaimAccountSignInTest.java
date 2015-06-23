package com.dvsa.mot.selenium.priv.frontend.openam.test;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.openam.OpenAMClaimAccountSignInPage;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;


public class OpenAMClaimAccountSignInTest extends BaseTest {


    @Test(groups = {"Regression", "VM-4868"}) public void testOpenAMClaimAccountWithMissMatchEmails() {

        Login login = createTester(true);
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver).navigateToClaimAccountPage(driver, login);
        openAMClaimAccountSignInPage.submitClaimAccountDetailsExpectingFailure(
                Person.getUnique(Person.PERSON_1, "testOpenAMClaimAccountWithMissMatchEmails"),
                Text.TEXT_PASSWORD_2);

        assertThat("Did not match email addresses",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
    }

    @Test(groups = {"Regression", "VM-4868"}) public void testEmailOptOutValidation() {

        Login login = createTester(true);
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver).navigateToClaimAccountPage(driver, login);
        openAMClaimAccountSignInPage.submitEmailSuccessfully(
                Person.getUnique(Person.PERSON_1, "testCorrect EmailEntered")).optOutEmailAddress()
                .submitPasswordSuccessfully(Text.TEXT_PASSWORD_2).clickOnSubmitButton();

        assertThat("No Email Addresses required",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

    }

    @Test(groups = {"Regression", "VM-2115"}) public void testValidAndInvalidPassword() {

        Login login = createTester(true);
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver).navigateToClaimAccountPage(driver, login);
        openAMClaimAccountSignInPage.submitEmailSuccessfully(
                Person.getUnique(Person.PERSON_1, "testCorrect EmailEntered")).blankPassword()
                .clickOnSubmitButton();

        assertThat("You must enter a password",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
        openAMClaimAccountSignInPage.enterPassword("Password1").enterConfirmPassword("")
                .clickOnSubmitButton();

        assertThat("confirm password not match",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
        openAMClaimAccountSignInPage.enterConfirmPassword("Password1").clickOnSubmitButton();
    }

}

