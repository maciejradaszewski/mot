package uk.gov.dvsa.ui.pages.cpms;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class TransactionDetailsPage extends Page {
    private static final String PAGE_TITLE = "Transaction details";

    @FindBy(id = "startChargeback") private WebElement reverseThisPaymentButton;

    public TransactionDetailsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public ReversePaymentSummaryPage clickReverseThisPaymentButton() {
        reverseThisPaymentButton.click();
        return new ReversePaymentSummaryPage(driver);
    }
}
