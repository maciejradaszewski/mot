package uk.gov.dvsa.ui.pages.specialnotices;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.ui.pages.Page;

public class SpecialNoticePreviewPage extends Page{

    @FindBy (id = "publish-special-notice") private WebElement publishSpecialNoticeButton;

    public SpecialNoticePreviewPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return publishSpecialNoticeButton.isDisplayed();
    }

    public SpecialNoticeAdminPage publishSpecialNotice() {
        publishSpecialNoticeButton.click();
        return new SpecialNoticeAdminPage(driver);
    }
}
