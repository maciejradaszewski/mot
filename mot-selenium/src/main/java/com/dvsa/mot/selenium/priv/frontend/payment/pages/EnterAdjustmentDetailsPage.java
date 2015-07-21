package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import com.dvsa.mot.selenium.framework.BasePage;

public class EnterAdjustmentDetailsPage extends BasePage {

    public static final String PAGE_TITLE = "MANUAL ADJUSTMENT";

    @FindBy(id = "inputAeNumber") private WebElement aeNumber;
    
    @FindBy(id = "date1-day") private WebElement chequeDay;

    @FindBy(id = "date1-month") private WebElement chequeMonth;

    @FindBy(id = "date1-year") private WebElement chequeYear;

    @FindBy(id = "slipNumber") private WebElement payingInSlipNumber;

    @FindBy(id = "chequeNumber") private WebElement chequeNumber;

    @FindBy(id = "accountName") private WebElement accountName;

    @FindBy(id = "amount") private WebElement amountOnCheque;

    @FindBy(id = "createOrder") private WebElement createOrderButton;

    public EnterAdjustmentDetailsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public EnterAdjustmentDetailsPage enterAeNumber(String aeRef) {
        aeNumber.sendKeys(aeRef);
        return this;
    }
    
    public EnterAdjustmentDetailsPage enterAdjustmentAmount(String amount) {
        amountOnCheque.clear();
        amountOnCheque.sendKeys(amount);
        return this;
    }

    public AdjustmentSummaryPage clickCreateOrderButton() {
        createOrderButton.click();
        return new AdjustmentSummaryPage(driver);
    }

}
