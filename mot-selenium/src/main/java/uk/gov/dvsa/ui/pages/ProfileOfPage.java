package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class ProfileOfPage extends Page{

    private static final String PAGE_TITLE = "Profile of";

    @FindBy(id="roles-and-associations-link") private WebElement rolesAndAssociationsLink;

    public ProfileOfPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public boolean isRolesAndAssociationsLinkDisplayed() {
        return driver.findElements(By.id("roles-and-associations-link")).size() > 0;
    }
}
