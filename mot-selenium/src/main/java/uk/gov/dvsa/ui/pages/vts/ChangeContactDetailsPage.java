package uk.gov.dvsa.ui.pages.vts;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.model.VtsChangePageTitle;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class ChangeContactDetailsPage extends Page {
    private String pageTitle = "";
    public static final String PATH = "/vehicle-testing-station/%s/%s/change";

    @FindBy (id = "email") private WebElement emailField;
    @FindBy (id = "BUSemailConfirmation") private WebElement confirmEmailField;
    @FindBy (id = "phoneTextBox") private WebElement telephoneField;
    @FindBy(id = "submitUpdate") private WebElement submitButton;
    @FindBy (id = "vtsAddressLine1") private WebElement contactDetailsAddressLine1;
    @FindBy (id = "town") private WebElement contactDetailsTown;
    @FindBy (id = "postcode") private WebElement contactDetailsPostconde;

    public ChangeContactDetailsPage(MotAppDriver driver, String pageTitle) {
        super(driver);
        this.pageTitle = pageTitle;
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), pageTitle);
    }

    public ChangeContactDetailsPage chooseOption(String name) {
        FormCompletionHelper.selectInputBox(driver.findElement(By.xpath(String.format("//label[contains(.,'%s')]", name))));
        return this;
    }

    public VehicleTestingStationPage clickSubmitButton() {
        submitButton.click();
        return new VehicleTestingStationPage(driver);
    }

    public ConfirmContactDetailsPage clickConfirmationSubmitButton() {
        submitButton.click();
        return new ConfirmContactDetailsPage(driver, VtsChangePageTitle.ReviewContactDetailsAddress.getText());
    }

    public ChangeContactDetailsPage changeFirstAddressLine(String street) {
        FormCompletionHelper.enterText(contactDetailsAddressLine1, street);
        return this;
    }

    public ChangeContactDetailsPage changeTown(String street) {
        FormCompletionHelper.enterText(contactDetailsTown, street);
        return this;
    }

    public ChangeContactDetailsPage changePostcode(String street) {
        FormCompletionHelper.enterText(contactDetailsPostconde, street);
        return this;
    }

    public ChangeContactDetailsPage inputContactDetailsEmail(String newEmail) {
        FormCompletionHelper.enterText(emailField, newEmail);
        return this;
    }

    public ChangeContactDetailsPage inputContactDetailsTelephone(String newTelephone) {
        FormCompletionHelper.enterText(telephoneField, newTelephone);
        return this;
    }
}
