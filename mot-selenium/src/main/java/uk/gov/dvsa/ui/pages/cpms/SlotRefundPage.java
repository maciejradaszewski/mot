package uk.gov.dvsa.ui.pages.cpms;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class SlotRefundPage extends Page {
    private static final String PAGE_TITLE = "Slot refund";

    @FindBy(id = "input_slots_number") private WebElement inputSlotsField;
    @FindBy(id = "inputReason") private WebElement inputReason;
    @FindBy(id = "startRefund") private WebElement continueButton;

    public SlotRefundPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }
    
    public SlotRefundPage enterSlotsToBeRefunded(int slots) {
        inputSlotsField.sendKeys(Integer.toString(slots));
        return this;
    }
    
    public SlotRefundSummaryPage selectRefundReasonAndContinue(String refundReason) {
        FormCompletionHelper.selectFromDropDownByVisibleText(inputReason, refundReason);
        continueButton.click();
        return new SlotRefundSummaryPage(driver);
    }
}
