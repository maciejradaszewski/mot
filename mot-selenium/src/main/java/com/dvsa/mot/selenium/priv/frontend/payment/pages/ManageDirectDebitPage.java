package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ManageDirectDebitPage extends BasePage {

    private static final String PAGE_TITLE = "MANAGE YOUR DIRECT DEBIT";

    public ManageDirectDebitPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    @FindBy(id = "cancelDirectDebit") private WebElement cancelDirectDebitLink;

    public CancelDirectDebitPage clickCancelDirectDebitLink() {
        cancelDirectDebitLink.click();
        return new CancelDirectDebitPage(driver);
    }

}
