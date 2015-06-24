package com.dvsa.mot.selenium.priv.frontend.openam;

import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class OpenAMClaimAccountSignInPage extends BasePage {

    @FindBy(id = "email") private WebElement email;

    @FindBy(id = "confirm_email") private WebElement confirmEmail;

    @FindBy(id = "emailOptOut") private WebElement emailOptOut;

    @FindBy(id = "password") private WebElement password;

    @FindBy(id = "confirm_password") private WebElement confirmPassword;

    @FindBy(id = "btSubmitForm") private WebElement submitFormButton;


    public OpenAMClaimAccountSignInPage(WebDriver driver) {
        super(driver);
    }

    public OpenAMClaimAccountSignInPage enterEmail(String emailAddress) {
        email.clear();
        email.sendKeys(emailAddress);
        return this;
    }

    public OpenAMClaimAccountSignInPage enterConfirmEmail(String emailAddress) {
        confirmEmail.clear();
        confirmEmail.sendKeys(emailAddress);
        return this;
    }

    public OpenAMClaimAccountSignInPage enterPassword(String yourPassword) {
        password.sendKeys(yourPassword);
        return this;
    }

    public OpenAMClaimAccountSignInPage enterConfirmPassword(String yourPassword) {
        confirmPassword.sendKeys(yourPassword);
        return this;
    }

    public OpenAMClaimAccountSecurityQuestionsPage clickOnSubmitButton() {
        submitFormButton.click();
        return new OpenAMClaimAccountSecurityQuestionsPage(driver);
    }

    public OpenAMClaimAccountSignInPage clickOnSubmitButtonExpectingToStayOnThisPage() {
        submitFormButton.click();
        return this;
    }

    public OpenAMClaimAccountSignInPage submitEmailSuccessfully(Person person) {
        enterEmail(person.email);
        enterConfirmEmail(person.email);
        return new OpenAMClaimAccountSignInPage(driver);
    }

    public OpenAMClaimAccountSignInPage submitMissMatchEmails(Person person) {
        enterEmail(person.email);
        enterConfirmEmail(person.wrongEmail);
        return new OpenAMClaimAccountSignInPage(driver);
    }

    public OpenAMClaimAccountSignInPage submitPasswordSuccessfully(String password) {
        enterPassword(password);
        enterConfirmPassword(password);
        return new OpenAMClaimAccountSignInPage(driver);
    }

    public OpenAMClaimAccountSignInPage submitClaimAccountDetailsExpectingFailure(Person person,
            String password) {
        submitMissMatchEmails(person);
        submitPasswordSuccessfully(password);
        submitFormButton.click();
        return new OpenAMClaimAccountSignInPage(driver);
    }

    public OpenAMClaimAccountSignInPage optOutEmailAddress() {
        emailOptOut.click();
        return new OpenAMClaimAccountSignInPage(driver);
    }

    public OpenAMClaimAccountSignInPage blankPassword() {
        password.clear();
        return this;
    }

    public OpenAMClaimAccountSignInPage enterNewPassword(String newPassword) {
        password.sendKeys(newPassword);
        return this;
    }

    public OpenAMClaimAccountSignInPage enterNewConfirmPassword(String newPassword) {
        confirmPassword.sendKeys(newPassword);
        return this;
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
