package uk.gov.dvsa.ui.pages.dvsa;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.navigation.MotPageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;


public class UserSearchPage extends Page {

    private static final String PAGE_TITLE = "User management";
    public static final String PATH = "/user-admin/search";

    @FindBy(id = "username") private WebElement usernameInputField;
    @FindBy(id = "email") private WebElement emailInputField;
    @FindBy(id = "validation-summary-message") private WebElement validationMessage;
    @FindBy(css = ".button") private WebElement searchButton;

    public UserSearchPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public UserSearchPage searchForUserByUserName(String username){
        FormCompletionHelper.enterText(usernameInputField, username);
        return new UserSearchPage(driver);
    }

    public UserSearchPage searchForUserByUserEmail(String email){
        FormCompletionHelper.enterText(emailInputField, email);
        return new UserSearchPage(driver);
    }

    public boolean isValidationMessageDisplayed() {
        return validationMessage.isDisplayed();
    }

    public <T extends Page> T clickSearchButton(Class<T> clazz) {
        searchButton.click();
        return MotPageFactory.newPage(driver, clazz);
    }
}
