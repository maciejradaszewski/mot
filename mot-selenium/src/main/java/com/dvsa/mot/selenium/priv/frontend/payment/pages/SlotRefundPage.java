package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class SlotRefundPage extends BasePage {

    private static final String PAGE_TITLE = "SLOT REFUND";

    @FindBy(id = "input_slots_number") private WebElement inputSlotsField;

    @FindBy(id = "startRefund") private WebElement continueButton;

    public SlotRefundPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public SlotRefundPage enterSlotsToBeRefunded(String slots) {
        inputSlotsField.sendKeys(slots);
        return new SlotRefundPage(driver);
    }

    public SlotRefundSummaryPage clickContinueToStartRefund() {
        continueButton.click();
        return new SlotRefundSummaryPage(driver);
    }

}
