package uk.gov.dvsa.ui.pages.dvsa;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class ManageRolesPage extends Page {

    private static final String PAGE_TITLE = "Manage roles";

    @FindBy(id = "remove-role-VEHICLE-EXAMINER") private WebElement removeRoleVELink;
    @FindBy(id = "remove-role-DVSA-AREA-OFFICE-2") private WebElement removeRoleAO2Link;
    @FindBy(id = "add-role-VEHICLE-EXAMINER") private WebElement addRoleVELink;
    @FindBy(id = "add-role-DVSA-AREA-OFFICE-2") private WebElement addRoleAO2User;
    @FindBy(id = "confirm-remove-role") private WebElement removeRoleButton;
    @FindBy(id = "confirm-add-role") private WebElement addRoleButton;
    @FindBy(id = "validation-message--success") private WebElement checkRoleNotification;

    public ManageRolesPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public void clickRemoveRoleVELink() {
        removeRoleVELink.click();
    }

    public void clickRemoveRoleAO2Link() {
        removeRoleAO2Link.click();
    }

    public void clickAddRoleVELink() {
        addRoleVELink.click();
    }

    public void clickAddRoleAO2User() {
        addRoleAO2User.click();
    }

    public void clickRemoveRoleButton() {
        removeRoleButton.click();
    }

    public void clickAddRoleButton() {
        addRoleButton.click();
    }

    public String getNotificationText() {
        return checkRoleNotification.getText();
    }
}
