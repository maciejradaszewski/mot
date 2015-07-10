package uk.gov.dvsa.ui.pages;


import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class PermissionPage extends Page {

    private static final String PAGE_TITLE = "MOT testing service\n" + "You don't have permission";

    public PermissionPage(MotAppDriver driver) {

        super(driver);
        selfVerify();
    }

    @Override protected boolean selfVerify() {

        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    @FindBy(className = "text") private WebElement permissionMessage;

    @FindBy(xpath = "//a[contains(., 'Return to user home')]") private WebElement returnToUserHome;

    public HomePage navigateToHomePage() {

        returnToUserHome.click();
        return new HomePage(driver);
    }
}
