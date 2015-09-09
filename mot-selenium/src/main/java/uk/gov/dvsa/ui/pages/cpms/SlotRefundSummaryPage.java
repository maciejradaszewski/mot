package uk.gov.dvsa.ui.pages.cpms;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class SlotRefundSummaryPage extends Page {
    private static final String PAGE_TITLE = "Slot refund summary";

    @FindBy(id = "refundSlots") private WebElement refundSlotsButton;

    public SlotRefundSummaryPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public SlotRefundConfirmationPage clickRefundSlotsButton() {
        refundSlotsButton.click();
        return new SlotRefundConfirmationPage(driver);
    }
}