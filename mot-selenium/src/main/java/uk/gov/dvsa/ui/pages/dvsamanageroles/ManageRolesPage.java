package uk.gov.dvsa.ui.pages.dvsamanageroles;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.helpdesk.HelpDeskUserProfilePage;

public class ManageRolesPage extends Page {

    private static final String PAGE_TITLE = "Manage roles";

    @FindBy(id = "remove-role-VEHICLE-EXAMINER") private WebElement removeRoleOfVehicleExaminer;
    @FindBy(id = "add-role-DVSA-AREA-OFFICE-2") private WebElement addRoleOfAreaOffice2User;
    @FindBy(id = "confirm-remove-role") private WebElement removeRole;
    @FindBy(id = "confirm-add-role") private WebElement addRole;
    @FindBy(id = "validation-message--success") private WebElement checkRoleNotification;

    public ManageRolesPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public ManageRolesPage removeRoleOfVehicleExaminer() {
        removeRoleOfVehicleExaminer.click();
        return this;
    }

    public ManageRolesPage addRoleOfAo2() {
        addRoleOfAreaOffice2User.click();
        return this;
    }

    public ManageRolesPage confirmRemoveRoleOfVehicleExaminer() {
        removeRole.click();
        return this;
    }

    public ManageRolesPage confirmAddRoleOfAo2() {
        addRole.click();
        return this;
    }

    public String checkRoleNotification() {
        return checkRoleNotification.getText();
    }
}
