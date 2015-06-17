package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class TransactionReversalSummaryPage extends BasePage {

    private static final String PAGE_TITLE = "REVERSE PAYMENT";

    @FindBy(id = "startChargeback") private WebElement confirmReverseButton;

    public TransactionReversalSummaryPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public TransactionReversalConfirmationPage clickConfirmReverseButton() {
        confirmReverseButton.click();
        return new TransactionReversalConfirmationPage(driver);
    }

}
