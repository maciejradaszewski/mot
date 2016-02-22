package uk.gov.dvsa.ui.pages.profile;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.dvsa.RolesAndAssociationsPage;

public class ProfileOfUserPage extends ProfilePage {

    private static final String PAGE_TITLE = "Profile of";
    public static final String PATH = "/profile/%s";


    @FindBy(id="roles-and-associations-link") private WebElement rolesAndAssociationsLink;

    public ProfileOfUserPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public boolean isRolesAndAssociationsLinkDisplayed() {
        return driver.findElements(By.id("roles-and-associations-link")).size() > 0;
    }

    @Override
    public RolesAndAssociationsPage clickRolesAndAssociationsLink() {
        return null;
    }
}
