package uk.gov.dvsa.ui.pages.mot;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.mot.MotRetestStartedPage;

public class StartRetestConfirmationPage extends Page {
    public final String path = "/start-retest-confirmation/%s";
    private final String PAGE_TITLE = "Start retest confirmation";

    @FindBy(id = "confirm_vehicle_confirmation") private WebElement startMotTestButton;

    public StartRetestConfirmationPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public MotRetestStartedPage clickStartMotTest() {
        startMotTestButton.click();
        return new MotRetestStartedPage(driver);
    }
}
