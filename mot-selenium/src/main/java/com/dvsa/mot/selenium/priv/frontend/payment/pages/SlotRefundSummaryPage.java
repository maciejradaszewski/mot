package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class SlotRefundSummaryPage extends BasePage {

    private static final String PAGE_TITLE = "SLOT REFUND SUMMARY";

    @FindBy(id = "refundSlots") private WebElement refundSlotsButton;

    public SlotRefundSummaryPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public SlotRefundConfirmationPage clickRefundSlotsButton() {
        refundSlotsButton.click();
        return new SlotRefundConfirmationPage(driver);
    }

}
