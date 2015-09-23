package uk.gov.dvsa.ui.pages.cpms;


import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class PurchaseHistoryPage extends Page {
    private static final String PAGE_TITLE = "Purchase history";
    public static final String PATH = "/slots/%s/transaction-history";

    @FindBy(xpath = "//*[contains(@id,'salesReference-')]") private WebElement firstTransactionReference;

    public PurchaseHistoryPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public TransactionDetailsPage clickFirstTransactionReference() {
        firstTransactionReference.click();
        return new TransactionDetailsPage(driver);
    }
}
