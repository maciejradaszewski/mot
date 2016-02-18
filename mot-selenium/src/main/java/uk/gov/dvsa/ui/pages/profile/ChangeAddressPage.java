package uk.gov.dvsa.ui.pages.profile;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.navigation.MotPageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.ProfilePage;

public class ChangeAddressPage extends Page {

    private static final String PAGE_TITLE = "Change address";

    @FindBy (id = "firstLine") private WebElement firstLineInput;
    @FindBy (id = "townOrCity") private WebElement townInput;
    @FindBy (id = "postcode") private WebElement postcodeInput;
    @FindBy (id = "reviewAddress") private WebElement reviewAddressButton;
    @FindBy (id = "cancel-and-return") private WebElement cancelAndReturnLink;
    @FindBy (css = ".validation-message") private WebElement fieldValidationMessage;
    @FindBy (css = ".validation-summary") private WebElement pageValidationMessage;

    public ChangeAddressPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public ChangeAddressPage fillFirstLine(String value) {
        FormCompletionHelper.enterText(firstLineInput, value);
        return this;
    }

    public ChangeAddressPage fillTown(String value) {
        FormCompletionHelper.enterText(townInput, value);
        return this;
    }

    public ChangeAddressPage fillPostcode(String value) {
        FormCompletionHelper.enterText(postcodeInput, value);
        return this;
    }

    public String getFieldValidationMessage() {
        return fieldValidationMessage.getText();
    }

    public String getPageValidationMessage() {
        return pageValidationMessage.getText();
    }

    public <T extends Page>T clickReviewAddress(Class<T> clazz) {
        reviewAddressButton.click();
        return MotPageFactory.newPage(driver, clazz);
    }

    public <T extends ProfilePage>T clickCancelAndReturn(Class<T> clazz) {
        cancelAndReturnLink.click();
        return MotPageFactory.newPage(driver, clazz);
    }
}
