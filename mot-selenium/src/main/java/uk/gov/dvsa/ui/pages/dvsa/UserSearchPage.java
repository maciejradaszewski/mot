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
    @FindBy(id = "firstName") private WebElement userFirstNameInputField;
    @FindBy(id = "email") private WebElement emailInputField;
    @FindBy(id = "town") private WebElement townInputField;
    @FindBy(id = "date1-day") private WebElement dayInputField;
    @FindBy(id = "date1-month") private WebElement monthInputField;
    @FindBy(id = "date1-year") private WebElement yearInputField;
    @FindBy(id = "validation-summary-message") private WebElement noResultsMessage;
    @FindBy(id = "validation-message--failure") private WebElement errorMessage;
    @FindBy(css = ".button") private WebElement searchButton;
    @FindBy(id = "show-additonal-search-criteria") private WebElement additionalSearchCriteriaLink;

    public UserSearchPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public UserSearchPage searchForUserByUsername(String username){
        FormCompletionHelper.enterText(usernameInputField, username);
        return this;
    }

    public UserSearchPage searchForUserByFirstName(String firstname) {
        FormCompletionHelper.enterText(userFirstNameInputField, firstname);
        return this;
    }

    public UserSearchPage searchForUserByUserEmail(String email){
        FormCompletionHelper.enterText(emailInputField, email);
        return this;
    }

    public UserSearchPage searchForUserByTown(String town) {
        FormCompletionHelper.enterText(townInputField, town);
        return this;
    }

    public UserSearchPage searchForUserByDateOfBirth(String date) {
        String[] dateValues = date.split("-");
        FormCompletionHelper.enterText(dayInputField, dateValues[0]);
        FormCompletionHelper.enterText(monthInputField, dateValues[1]);
        FormCompletionHelper.enterText(yearInputField, dateValues[2]);
        return this;
    }

    public UserSearchPage clickAdditionalSearchCriteriaLink() {
        additionalSearchCriteriaLink.click();
        return this;
    }

    public boolean isErrorMessageDisplayed() {
        return errorMessage.isDisplayed();
    }

    public boolean isNoResultsMessageDisplayed() {
        return noResultsMessage.isDisplayed();
    }

    public boolean isSearchButtonDisplayed() {
        return searchButton.isDisplayed();
    }

    public String getValidationMessageText() {
        return noResultsMessage.getText();
    }

    public <T extends Page> T clickSearchButton(Class<T> clazz) {
        searchButton.click();
        return MotPageFactory.newPage(driver, clazz);
    }
}
