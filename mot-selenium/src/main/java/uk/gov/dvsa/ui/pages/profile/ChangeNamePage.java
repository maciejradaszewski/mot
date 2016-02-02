package uk.gov.dvsa.ui.pages.profile;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.navigation.MotPageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.ProfilePage;

public class ChangeNamePage extends Page {

    private static final String PAGE_TITLE = "Change name";

    @FindBy (id = "firstName") private WebElement firstNameInput;
    @FindBy (id = "middleName") private WebElement middleNameInput;
    @FindBy (id = "lastName") private WebElement lastNameInput;
    @FindBy (id = "submitNameChange") private WebElement submitNameChangeButton;
    @FindBy (id = "cancel-and-return") private WebElement cancelAndReturnLink;
    @FindBy (id = "validation-summary-id") private WebElement validationMessage;

    public ChangeNamePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public WebElement getFirstNameInputElement() {
        return firstNameInput;
    }

    public WebElement getLastNameInputElement() {
        return lastNameInput;
    }

    public <T extends Page>T clickSubmitButton(Class<T> clazz) {
        submitNameChangeButton.click();
        return MotPageFactory.newPage(driver, clazz);
    }

    public ProfilePage clickCancelAndReturnLink() {
        return new NewUserProfilePage(driver);
    }

    public String getValidationMessage() {
        return validationMessage.getText();
    }
}
