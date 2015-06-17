package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class TransactionReversalConfirmationPage extends BasePage {

    private static final String PAGE_TITLE = "REVERSE PAYMENT";

    @FindBy(id = "successMessage") private WebElement successfulMessage;

    @FindBy(id = "failureMessage") private WebElement failureMessage;

    @FindBy(id = "cancelAndReturn") private WebElement returnToTransactionDetailsLink;

    public TransactionReversalConfirmationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getReversalSuccessfulMessage() {
        return successfulMessage.getText();
    }

    public String getReversalFailureMessage() {
        return failureMessage.getText();
    }

    public PaymentDetailsPage clickReturnToTransactionDetailsLink() {
        returnToTransactionDetailsLink.click();
        return new PaymentDetailsPage(driver);
    }

}
