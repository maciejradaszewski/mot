package uk.gov.dvsa.ui.pages.vts;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class ChangeContactDetailsPage extends Page {
    private static final String PAGE_TITLE = "Change contact details";
    public static final String PATH = "/vehicle-testing-station/%s/contact-details";

    @FindBy (id = "BUSemail") private WebElement emailField;
    @FindBy (id = "BUSemailConfirmation") private WebElement confirmEmailField;
    @FindBy (id = "BUSphoneNumber") private WebElement telephoneField;
    @FindBy (id = "submitAeEdit") private WebElement saveContactDetailsButton;

    public ChangeContactDetailsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public ChangeContactDetailsPage editEmailAndConfirmEmail(String email, String confirmEmail){
        FormCompletionHelper.enterText(emailField, email);
        FormCompletionHelper.enterText(confirmEmailField, confirmEmail);

        return this;
    }

    public ChangeContactDetailsPage editTelephoneNumber(String newPhoneNumber){
        FormCompletionHelper.enterText(telephoneField, newPhoneNumber);

        return this;
    }

    public VehicleTestingStationPage clickSaveContactDetails(){
        saveContactDetailsButton.click();

        return new VehicleTestingStationPage(driver);
    }
}
