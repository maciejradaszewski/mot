package com.dvsa.mot.selenium.priv.frontend.openam;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ChangePasswordPage extends BasePage {

    private final String PAGE_TITLE = "";

    @FindBy(id = "password") private WebElement passwordField;
    @FindBy(id = "passwordConfirm") private WebElement passwordConfirmationField;
    @FindBy(id = "submitPass") private WebElement submitPasswordChanges;

    public ChangePasswordPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public UserDashboardPage enterNewPasswordAndSubmitChangesSuccessfully() {
        String password = RandomDataGenerator.generatePassword(8);
        passwordField.sendKeys(password);
        passwordConfirmationField.sendKeys(password);
        submitPasswordChanges.click();
        return new UserDashboardPage(driver);
    }

    public ChangePasswordPage enterNewPasswordAndSubmitExpectingFailure() {
        passwordField.sendKeys("Password1");
        passwordConfirmationField.sendKeys("INCORRECTPASS");
        submitPasswordChanges.click();
        return new ChangePasswordPage(driver);
    }

    public boolean isValidationMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
