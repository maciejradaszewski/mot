package uk.gov.dvsa.ui.pages.profile;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.ChangeEmailDetailsPage;
import uk.gov.dvsa.ui.pages.ProfilePage;
import uk.gov.dvsa.ui.pages.changedriverlicence.ChangeDrivingLicencePage;
import uk.gov.dvsa.ui.pages.dvsa.ManageRolesPage;
import uk.gov.dvsa.ui.pages.dvsa.RolesAndAssociationsPage;

public class NewUserProfilePage extends ProfilePage {
    private static final String PAGE_TITLE = "User profile";
    public static final String PATH = "/user-admin/user/%s";

    @FindBy(id = "manage-roles") private WebElement selectManageRolesLink;
    @FindBy(css = "#display-name a") private WebElement changeNameLink;
    @FindBy(id = "validation-message--success") private WebElement messageSuccess;
    @FindBy(id = "roles-and-associations") private WebElement roleAndAssociationLink;
    @FindBy(id = "drivingLicence") private WebElement drivingLicence;
    @FindBy(id = "account_management") private WebElement accountManagement;
    @FindBy(id = "qualification_status") private WebElement qualificationStatus;
    @FindBy(id = "dvsa_roles") private WebElement dvsaRoles;
    @FindBy(id = "change-group-a-qualification") private WebElement groupA_qualification;
    @FindBy(id = "change-group-b-qualification") private WebElement groupB_qualification;
    @FindBy(id = "personal_details") private WebElement personalDetailsTable;
    @FindBy(id = "dateOfBirth") private WebElement dateOfBirth;
    @FindBy(css = "#drivingLicence a") protected WebElement changeDrivingLicenceLink;
    @FindBy(css = "#email-address a") protected WebElement changeEmailLink;
    @FindBy(css = "#date-of-birth a") protected WebElement changeDOBLink;
    @FindBy(css = "#email-address") private WebElement userEmail;
    @FindBy(css = "#drivingLicence span") protected WebElement personDrivingLicenceRegion;

    public NewUserProfilePage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }

    @Override
    public ManageRolesPage clickManageRolesLink(){
        selectManageRolesLink.click();
        return new ManageRolesPage(driver);
    }

    @Override
    public boolean isRolesAndAssociationsLinkDisplayed() {
        return PageInteractionHelper.isElementDisplayed(roleAndAssociationLink);
    }

    @Override
    public boolean drivingLicenceIsDisplayed() {
        return PageInteractionHelper.isElementDisplayed(drivingLicence);
    }

    @Override
    public String getDrivingLicenceForPerson() {
        return drivingLicence.getText();
    }

    @Override
    public boolean addEditDrivingLicenceLinkExists() {
        return PageInteractionHelper.isElementDisplayed(changeDrivingLicenceLink);
    }

    @Override
    public String getDrivingLicenceRegionForPerson() {
        return personDrivingLicenceRegion.getText();
    }

    @Override
    public String getMessageSuccess(){
        return messageSuccess.getText();
    }

    public boolean isSuccessMessageDisplayed() {
        return messageSuccess.isDisplayed();
    }

    public boolean isQualificationStatusSectionIsDisplayed() {
        return PageInteractionHelper.isElementDisplayed(qualificationStatus);
    }

    public ChangeDrivingLicencePage clickChangeDrivingLicenceLink() {
        changeDrivingLicenceLink.click();
        return new ChangeDrivingLicencePage(driver);
    }

    @Override
    public boolean isChangeDrivingLicenceLinkIsDisplayed() {
        return PageInteractionHelper.isElementDisplayed(changeDrivingLicenceLink);
    }

    @Override
    public boolean isChangeDOBLinkIsDisplayed() {
        return PageInteractionHelper.isElementDisplayed(changeDOBLink);
    }

    @Override
    public boolean isDrivingLicenceInformationIsDisplayed() {
        return PageInteractionHelper.isElementDisplayed(drivingLicence);
    }

    @Override
    public boolean isAccountManagementSectionDisplayed() {
        return accountManagement.isDisplayed();
    }

    @Override
    public boolean isChangeQualificationLinksDisplayed() {
        return PageInteractionHelper.isElementDisplayed(groupA_qualification) &&
        PageInteractionHelper.isElementDisplayed(groupB_qualification);
    }

    @Override
    public boolean isChangeEmailLinkIsDisplayed() {
        return PageInteractionHelper.isElementDisplayed(changeEmailLink);
    }

    @Override
    public boolean isDvsaRolesSectionIsDisplayed() {
        return PageInteractionHelper.isElementDisplayed(dvsaRoles);
    }

    @Override
    public RolesAndAssociationsPage clickRolesAndAssociationsLink() {
        roleAndAssociationLink.click();
        return new RolesAndAssociationsPage(driver);
    }

    @Override
    public boolean isChangeNameLinkDisplayed() {
        return PageInteractionHelper.isElementDisplayed(changeNameLink);
    }

    public ChangeNamePage clickChangeNameLink() {
        changeNameLink.click();
        return new ChangeNamePage(driver);
    }

    public ChangeDateOfBirthPage clickChangeDOBLink() {
        changeDOBLink.click();
        return new ChangeDateOfBirthPage(driver);
    }

    @Override
    public ChangeEmailDetailsPage clickChangeEmailLink() {
        changeEmailLink.click();
        return new ChangeEmailDetailsPage(driver);
    }

    public boolean verifyEmailIsChanged(String email) {
        return userEmail.getText().contains(email);
    }

    public boolean isPageLoaded() {
        return selfVerify();
    }
}
