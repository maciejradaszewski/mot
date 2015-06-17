package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class SlotRefundConfirmationPage extends BasePage {

    private static final String PAGE_TITLE = "SLOT REFUND CONFIRMATION";

    @FindBy(id = "successMessage") private WebElement successMessage;

    public SlotRefundConfirmationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getRefundSuccessMessage() {
        return successMessage.getText();
    }

}
