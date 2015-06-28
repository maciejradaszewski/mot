package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class CancelDirectDebitPage extends BasePage {

    private static final String PAGE_TITLE = "CANCEL YOUR DIRECT DEBIT";
    
    @FindBy(id = "cancelMandate") private WebElement cancelMandateButton;

    public CancelDirectDebitPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public DirectDebitCancelConfirmationPage clickCancelMandateButton() {
        cancelMandateButton.click();
        return new DirectDebitCancelConfirmationPage(driver);
    }

}
