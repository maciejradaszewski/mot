package uk.gov.dvsa.ui.pages.dvsarolesandmanagement;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class DvsaManagesRolesPage extends Page {

    public static final String PATH = "/user-admin/user-profile/%s/manage-internal-role";
    private static final String PAGE_TITLE = "User profile";

    @FindBy(id = "add-role-DVSA-AREA-OFFICE-2")
    private WebElement areaOffcieOTwoAddRoleLink;

    @FindBy(id = "confirm-add-role")
    private WebElement confirmAddRoleButton;

    @FindBy(id = "confirm-remove-role")
    private WebElement confirmRemoveRoleButton;

    @FindBy(id = "remove-role-DVSA-AREA-OFFICE-2")
    private WebElement removeRoleDvsaAreaOfficeTwoLink;

    public DvsaManagesRolesPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public DvsaManagesRolesPage clickToAssignAreaOfficeInternalRole() {
        areaOffcieOTwoAddRoleLink.click();

        return this;
    }

    public DvsaManagesRolesPage clickConfirmAddRoleButton() {
        confirmAddRoleButton.click();
        return this;
    }

    public DvsaManagesRolesPage clickConfirmRemoveRoleButton(){
        confirmRemoveRoleButton.click();
        return this;
    }

    public DvsaManagesRolesPage clickToRemoveAreaOfficeInternalRole() {
        removeRoleDvsaAreaOfficeTwoLink.click();

        return this;
    }
}
