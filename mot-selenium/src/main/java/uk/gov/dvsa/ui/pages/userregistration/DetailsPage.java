package uk.gov.dvsa.ui.pages.userregistration;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.ContactDetailsHelper;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class DetailsPage extends Page {

    private static final String PAGE_TITLE = "Your details";

    @FindBy(id = "firstName") private WebElement firstName;

    @FindBy(id = "middleName") private WebElement middleName;

    @FindBy(id = "lastName") private WebElement lastName;

    @FindBy(id = "emailAddress") private WebElement emailAddress;

    @FindBy(id = "confirmEmailAddress") private WebElement verifyEmailAddress;

    @FindBy(id = "continue") private WebElement continueToNextPage;

    public DetailsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public AddressPage enterDetailsAndSubmitExpectingAddressPage(String name, String surname, String email, String emailTwo) {
        FormCompletionHelper.enterText(firstName, name);
        FormCompletionHelper.enterText(lastName, surname);
        FormCompletionHelper.enterText(emailAddress, email);
        FormCompletionHelper.enterText(verifyEmailAddress, emailTwo);
        continueToNextPage.click();
        return new AddressPage(driver);
    }

    public AddressPage clickContinue() {
        continueToNextPage.click();
        return new AddressPage(driver);
    }

    public DetailsPage enterYourDetaisl()
    {
        String email = ContactDetailsHelper.getEmail();
        FormCompletionHelper.enterText(firstName, ContactDetailsHelper.generateUniqueName());
        FormCompletionHelper.enterText(lastName, ContactDetailsHelper.generateUniqueName());
        FormCompletionHelper.enterText(emailAddress, email);
        FormCompletionHelper.enterText(verifyEmailAddress, email);

        return this;
    }
}
