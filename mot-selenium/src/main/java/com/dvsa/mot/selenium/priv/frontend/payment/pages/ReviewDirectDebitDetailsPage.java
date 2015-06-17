package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ReviewDirectDebitDetailsPage extends BasePage {

    private static final String PAGE_TITLE = "REVIEW DIRECT DEBIT DETAILS";

    @FindBy(id = "setupMandate") private WebElement continueToGoCardlessButton;

    public ReviewDirectDebitDetailsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public DirectDebitForm clickContinueToGoCardlessButton() {
        continueToGoCardlessButton.click();
        return new DirectDebitForm(driver);
    }

}
