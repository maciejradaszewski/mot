package uk.gov.dvsa.ui.pages.dvsarolesandmanagement;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class DvsaEventHistoryPage extends Page {

    public static final String PATH = "/event/list/person/%s";
    private static final String PAGE_TITLE = "Events History";

    @FindBy(linkText = "Role Association Change")
    private WebElement eventHistoryList;

    public DvsaEventHistoryPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public boolean isEvenHistoryDisplayed() {
        return PageInteractionHelper.waitForElementToBeClickable(eventHistoryList).isDisplayed();
    }
}
