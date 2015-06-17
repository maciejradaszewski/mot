package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ChequePaymentOrderSummaryPage extends BasePage {

    private static final String PAGE_TITLE = "ORDER SUMMARY";

    @FindBy(id = "confirmOrder") private WebElement confirmOrderButton;

    public ChequePaymentOrderSummaryPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public ChequePaymentOrderConfirmedPage clickConfirmOrderButton() {
        confirmOrderButton.click();
        return new ChequePaymentOrderConfirmedPage(driver);
    }

}
