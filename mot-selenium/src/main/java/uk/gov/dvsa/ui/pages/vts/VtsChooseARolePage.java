package uk.gov.dvsa.ui.pages.vts;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class VtsChooseARolePage extends Page {

    private static final String PAGE_TITLE = "Vehicle Testing Station\n" +
            "Choose a role";

    @FindBy(id = "site-role-TESTER" ) private WebElement testerRole;

    @FindBy(id = "site-role-SITE-MANAGER" ) private WebElement siteManagerRole;

    @FindBy(id = "site-role-SITE-ADMIN" ) private WebElement siteAdminRole;

    @FindBy(id = "assign-role-button" ) private WebElement selectButton;

    public VtsChooseARolePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public VtsChooseARolePage selectSiteManagerRole() {
        siteManagerRole.click();

        return this;
    }

    public VtsChooseARolePage selectSiteAdminRole() {
        siteAdminRole.click();

        return this;
    }

    public VtsChooseARolePage clickSelectButton() {
        selectButton.click();

        return this;
    }
}
