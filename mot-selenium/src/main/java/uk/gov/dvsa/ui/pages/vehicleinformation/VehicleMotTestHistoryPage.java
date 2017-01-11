package uk.gov.dvsa.ui.pages.vehicleinformation;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class VehicleMotTestHistoryPage extends Page {

    private static final String PAGE_TITLE = "Vehicle MOT test history";

    @FindBy(xpath = "//*[@title='Non-Mot Test']") private WebElement nonMotTitleElement;

    public VehicleMotTestHistoryPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public boolean isNonMotTestDisplayed() {
        return PageInteractionHelper.isElementDisplayed(nonMotTitleElement);
    }
}
