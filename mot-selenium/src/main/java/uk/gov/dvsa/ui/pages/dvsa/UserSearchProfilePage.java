package uk.gov.dvsa.ui.pages.dvsa;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class UserSearchProfilePage extends Page{

    private static final String PAGE_TITLE = "User profile";
    @FindBy(id = "manage-internal-roles") private WebElement selectManageRolesLink;

    public UserSearchProfilePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public ManageRolesPage clickManageRolesLink(){
        selectManageRolesLink.click();
        return new ManageRolesPage(driver);
    }
}
