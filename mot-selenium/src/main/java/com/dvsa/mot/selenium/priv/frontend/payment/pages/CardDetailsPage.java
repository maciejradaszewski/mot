package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.datasource.Payments;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;

public class CardDetailsPage extends BasePage {

    private static final String PAGE_TITLE = "CARD DETAILS";

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

    public CardDetailsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public CardDetailsPage enterCardDetails(Payments payments) {
        cardHolderName.sendKeys(payments.cardHolderName);
        cardNumber.sendKeys(payments.cardNumber);
        enterExpiryDate(payments);
        enterStartDate(payments);
        securityCode.sendKeys(payments.securityCode);
        return new CardDetailsPage(driver);
    }

    public CardDetailsPage enterExpiryDate(Payments payments) {
        Select selectExpiryMonth = new Select(expiryMonth);
        selectExpiryMonth.selectByVisibleText(payments.cardEndMonth);
        expiryYear.sendKeys(payments.cardEndYear);
        return new CardDetailsPage(driver);
    }

    public CardDetailsPage enterStartDate(Payments payments) {
        Select selectStartMonth = new Select(startMonth);
        selectStartMonth.selectByVisibleText(payments.cardStartMonth);
        startYear.sendKeys(payments.cardStartYear);
        return new CardDetailsPage(driver);
    }

    public PaymentConfirmationPage clickPayNowButton() {
        payNowButton.click();
        return new PaymentConfirmationPage(driver);
    }

    public BuySlotsPage clickCancelButton() {
        cancelButton.click();
        return new BuySlotsPage(driver);
    }

}
