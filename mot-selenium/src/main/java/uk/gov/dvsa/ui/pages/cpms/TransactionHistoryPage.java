package uk.gov.dvsa.ui.pages.cpms;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class TransactionHistoryPage extends Page {

    private static final String PAGE_TITLE = "Purchase history";

    @FindBy(xpath = "(//td[@class='numeric'])[1]") private WebElement adjustmentQuantity;

    public TransactionHistoryPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    public String getAdjustmentQuantity() {
        return adjustmentQuantity.getText();
    }

    @Override
    protected boolean selfVerify() {
        System.out.print(adjustmentQuantity.getText());
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }
}
