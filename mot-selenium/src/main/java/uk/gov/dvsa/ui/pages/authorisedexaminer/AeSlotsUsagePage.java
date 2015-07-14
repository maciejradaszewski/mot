package uk.gov.dvsa.ui.pages.authorisedexaminer;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class AeSlotsUsagePage extends Page {
    private static final String PAGE_TITLE = "Test slot usage";
    public static final String PATH = "/slots/%s/slot-usage";

    @FindBy(css = ".slot-hero__usage ul") private WebElement slotUsageMessage;

    public AeSlotsUsagePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public String getSlotUsageCountMessage() {
        return slotUsageMessage.getText();
    }
}
