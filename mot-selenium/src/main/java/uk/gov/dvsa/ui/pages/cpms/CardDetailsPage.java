package uk.gov.dvsa.ui.pages.cpms;

import org.joda.time.DateTime;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.helper.RandomDataGenerator;
import uk.gov.dvsa.ui.pages.Page;

public class CardDetailsPage extends Page {
    private static final String PAGE_TITLE = "Card Details";
    
    @FindBy(id = "cardholderName") private WebElement cardHolderName;
    @FindBy(id = "cardNumber") private WebElement cardNumber;
    @FindBy(id = "expiryMonth") private WebElement expiryMonth;
    @FindBy(id = "expiryYear") private WebElement expiryYear;
    @FindBy(id = "issueNumber") private WebElement issueNumber;
    @FindBy(id = "startMonth") private WebElement startMonth;
    @FindBy(id = "startYear") private WebElement startYear;
    @FindBy(id = "securityCode") private WebElement securityCode;
    @FindBy(id = "submitButton") private WebElement payNowButton;
    @FindBy(id = "cancelButton") private WebElement cancelButton;

    public CardDetailsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }
    
    public CardPaymentConfirmationPage enterCardDetailsAndSubmit() {
        cardHolderName.sendKeys(RandomDataGenerator.generateRandomString());
        cardNumber.sendKeys("4006000000000600");
        enterExpiryDate();
        enterStartDate();
        securityCode.sendKeys(RandomDataGenerator.generateRandomNumber(3, hashCode()));
        payNowButton.click();
        return new CardPaymentConfirmationPage(driver);
    }

    public CardDetailsPage enterExpiryDate() {
        FormCompletionHelper.selectFromDropDownByValue(expiryMonth, String.valueOf(DateTime.now().getMonthOfYear()));
        expiryYear.sendKeys(String.valueOf(DateTime.now().plusYears(1).getYear()));
        return this;
    }

    public CardDetailsPage enterStartDate() {
        FormCompletionHelper.selectFromDropDownByValue(startMonth, String.valueOf(DateTime.now().getMonthOfYear()));
        startYear.sendKeys(String.valueOf(DateTime.now().getYear()));
        return this;
    }

    public BuyTestSlotsPage clickCancelButton() {
        cancelButton.click();
        return new BuyTestSlotsPage(driver);
    }
}
