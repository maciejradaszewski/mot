package uk.gov.dvsa.ui.pages.cpms;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class PaymentReversalConfirmationPage extends Page {
    private static final String PAGE_TITLE = "Reverse payment";

    @FindBy(id = "successMessage") private WebElement reversalSuccessfulMessage;

    public PaymentReversalConfirmationPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public boolean isReversalSuccessfulMessageDisplayed() {
        return reversalSuccessfulMessage.isDisplayed();
    }
}
