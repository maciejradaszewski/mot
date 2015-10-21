package uk.gov.dvsa.ui.pages.login;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.interfaces.DisplayMessage;
import uk.gov.dvsa.ui.pages.Page;

public class LockOutWarningDasEnabledPage extends Page implements DisplayMessage {

    private static final String PAGE_TITLE = "MOT testing service";
    private static final String LOCKOUT_WARNING_MESSAGE = "Warning: Account lockout will occur after next 1 password failure(s).";

    @FindBy(linkText = "Return to sign in") private WebElement returnToSignInLink;
    @FindBy(className = "text") private WebElement warningSection;

    public LockOutWarningDasEnabledPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    @Override
    public boolean isMessageDisplayed() {
        return warningSection.findElement(By.xpath(".//p[2]")).getText().contains(LOCKOUT_WARNING_MESSAGE);
    }
}
