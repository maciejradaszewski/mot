package uk.gov.dvsa.ui.pages.dvsa;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.AbstractProfilePage;
import uk.gov.dvsa.ui.pages.changedriverlicence.ChangeDrivingLicencePage;

public class UserSearchProfilePage extends AbstractProfilePage {

    private static final String PAGE_TITLE = "User profile";
    public static final String PATH = "/user-admin/search";

    @FindBy(id = "manage-internal-roles") private WebElement selectManageRolesLink;
    @FindBy(id = "person-driving-licence") private WebElement personDrivingLicence;
    @FindBy(id = "person-driving-licence-number") private WebElement personDrivingLicenceNumber;
    @FindBy(id = "person-driving-licence-region") private WebElement personDrivingLicenceRegion;
    @FindBy(id = "add-edit-driving-licence-link") private WebElement changeDrivingLicenceLink;
    @FindBy(id = "validation-message--success") private WebElement messageSuccess;
    @FindBy(id="roles-and-associations-link") private WebElement rolesAndAssociationsLink;

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

    public RolesAndAssociationsPage clickRolesAndAssociationsLink() {
        rolesAndAssociationsLink.click();
        return new RolesAndAssociationsPage(driver);
    }
    
    public ChangeDrivingLicencePage clickChangeDrivingLicenceLink() {
        changeDrivingLicenceLink.click();
        return new ChangeDrivingLicencePage(driver);
    }

    public String getDrivingLicenceForPerson() {
        return personDrivingLicenceNumber.getText();
    }

    public String getDrivingLicenceRegionForPerson() {
        return personDrivingLicenceRegion.getText();
    }

    public boolean isSuccessMessageDisplayed(){
        return messageSuccess.isDisplayed();
    }

    public String getMessageSuccess(){
        return messageSuccess.getText();
    }
}
