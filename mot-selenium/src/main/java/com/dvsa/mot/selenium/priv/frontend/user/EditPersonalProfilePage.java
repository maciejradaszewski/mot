package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class EditPersonalProfilePage extends BasePage {

    @FindBy(id = "firstName") private WebElement firstName;

    @FindBy(id = "middleName") private WebElement middleName;

    @FindBy(id = "surname") private WebElement surname;

    @FindBy(id = "drivingLicenceNumber") private WebElement drivingLicenceNumber;

    @FindBy(id = "email") private WebElement email;

    @FindBy(id = "emailConfirmation") private WebElement emailConfirmation;

    @FindBy(id = "update-profile") private WebElement updateProfile;

    public EditPersonalProfilePage(WebDriver driver) {
        super(driver);
        checkTitle(PageTitles.UPDATE_PROFILE_DETAILS_TITLE.getPageTitle());
    }

    public static EditPersonalProfilePage navigateHereFromLoginPage(WebDriver driver, Login login) {
        return UserPersonalProfilePage.navigateHereFromLoginPage(driver, login).editUserProfile();
    }

    public EditPersonalProfilePage setEmail(String value) {
        email.clear();
        email.sendKeys(value);
        return this;
    }

    public String getEmail() {
        return email.getAttribute("value");
    }

    public EditPersonalProfilePage setEmailConfirmation(String value) {
        emailConfirmation.clear();
        emailConfirmation.sendKeys(value);
        return this;
    }

    public String getEmailConfirmation() {
        return emailConfirmation.getAttribute("value");
    }

    public UserPersonalProfilePage clickUpdateProfile() {
        updateProfile.click();
        return new UserPersonalProfilePage(driver);
    }

    public EditPersonalProfilePage clickUpdateProfileExpectingError() {
        updateProfile.click();
        return new EditPersonalProfilePage(driver);
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
