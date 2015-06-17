package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.datasource.ChequePayment;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import org.joda.time.DateTime;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class EnterChequeDetailsPage extends BasePage {

    private static final String PAGE_TITLE = "ENTER CHEQUE DETAILS";

    @FindBy(id = "date1-day") private WebElement chequeDay;

    @FindBy(id = "date1-month") private WebElement chequeMonth;

    @FindBy(id = "date1-year") private WebElement chequeYear;

    @FindBy(id = "slipNumber") private WebElement payingInSlipNumber;

    @FindBy(id = "chequeNumber") private WebElement chequeNumber;

    @FindBy(id = "accountName") private WebElement accountName;

    @FindBy(id = "amount") private WebElement amountOnCheque;

    @FindBy(id = "createOrder") private WebElement createOrderButton;

    @FindBy(id = "validation-message--failure") private WebElement validationErrorMessage;

    public EnterChequeDetailsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public EnterChequeDetailsPage enterChequeDate() {
        chequeDay.sendKeys((Integer.toString(DateTime.now().getDayOfMonth())));
        chequeMonth.sendKeys((Integer.toString(DateTime.now().getMonthOfYear())));
        chequeYear.sendKeys((Integer.toString(DateTime.now().getYear())));
        return new EnterChequeDetailsPage(driver);
    }

    public EnterChequeDetailsPage enterChequeDetails(ChequePayment chequePayment) {
        enterChequeDate();
        payingInSlipNumber.sendKeys(RandomDataGenerator.generateRandomNumber(6, this.hashCode()));
        chequeNumber.sendKeys(RandomDataGenerator.generateRandomNumber(7, this.hashCode()));
        accountName.sendKeys(RandomDataGenerator.generateRandomString(8, this.hashCode()));
        amountOnCheque.sendKeys(chequePayment.amountOnCheque);
        return new EnterChequeDetailsPage(driver);
    }

    public ChequePaymentOrderSummaryPage clickCreateOrderButton() {
        createOrderButton.click();
        return new ChequePaymentOrderSummaryPage(driver);
    }

    public EnterChequeDetailsPage clickCreateOrderButtonWithExcessAmount() {
        createOrderButton.click();
        return new EnterChequeDetailsPage(driver);
    }

    public boolean isValidationErrorMessageDisplayed() {
        return validationErrorMessage.isDisplayed();
    }

}
