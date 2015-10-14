package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.navigation.MotPageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class ChangePasswordFromProfilePage extends Page {

    public static final String PATH = "/profile/change-password";
    public static final String PAGE_TITLE = "Your account\n" + "Change your password";

    @FindBy(id = "oldPassword") private WebElement oldPassword;
    @FindBy(id = "password") private WebElement newPassword;
    @FindBy(id = "passwordConfirm") private WebElement passwordConfirm;
    @FindBy(id = "submitPass") private WebElement submitButton;
    @FindBy(id = "cancelLink") private WebElement cancelLink;
    @FindBy(id = "validation-summary-id") private WebElement errorMassagesWindow;

    public ChangePasswordFromProfilePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public ChangePasswordFromProfilePage enterOldPassword(String password) {
        FormCompletionHelper.enterText(oldPassword, password);
        return this;
    }

    public ChangePasswordFromProfilePage enterNewPassword(String password) {
        FormCompletionHelper.enterText(newPassword, password);
        return this;
    }

    public ChangePasswordFromProfilePage confirmNewPassword(String password) {
        FormCompletionHelper.enterText(passwordConfirm, password);
        return this;
    }

    public ProfilePage clickCancelLink() {
        cancelLink.click();
        return new ProfilePage(driver);
    }

    public <T extends Page> T clickSubmitButton(Class<T> clazz) {
        submitButton.click();
        return MotPageFactory.newPage(driver, clazz);
    }

    public boolean isErrorMessageWindowDisplayed() {
        return errorMassagesWindow.isDisplayed();
    }

    public String getErrorMessage() {
        return errorMassagesWindow.getText();
    }
}
