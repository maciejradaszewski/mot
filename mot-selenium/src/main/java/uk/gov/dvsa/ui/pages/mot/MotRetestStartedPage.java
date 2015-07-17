package uk.gov.dvsa.ui.pages.mot;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.Page;

public class MotRetestStartedPage extends Page {
    private static final String PAGE_TITLE = "MOT retest started";

    @FindBy(id = "sign-out") private WebElement signOut;
    @FindBy(id = "return_to_home") private WebElement returnToHome;

    public MotRetestStartedPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public HomePage clickReturnToHome() {
        returnToHome.click();

        return new HomePage(driver);
    }
}
