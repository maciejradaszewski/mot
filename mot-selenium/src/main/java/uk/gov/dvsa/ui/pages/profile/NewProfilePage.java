package uk.gov.dvsa.ui.pages.profile;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.AbstractProfilePage;

public class NewProfilePage extends AbstractProfilePage {

    public static final String PATH = "/preview/profile/%s";
    private static final String PAGE_TITLE = "profile";

    @FindBy(id = "personal_details") private WebElement personalDetails;
    @FindBy(id = "contact_details") private WebElement contactDetails;
    @FindBy(id = "qualification_status") private WebElement qualificationStatus;
    @FindBy(id = "account_management") private WebElement accountManagement;
    @FindBy(id = "related") private WebElement related;
    @FindBy(id = "account_security") private WebElement accountSecurity;


    private static String DVSA_ROLES = "dvsa_roles";
    private static String QUALIFICATION_STATUS = "qualification_status";
    private static String GROUP_A_QUALIFICATION = "change-group-a-qualification";
    private static String GROUP_B_QUALIFICATION = "change-group-b-qualification";

    public NewProfilePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public boolean isChangeDrivingLicenceLinkIsDisplayed() {
        return !personalDetails.findElements(By.xpath(".//*[@id='drivingLicence']/a")).isEmpty();
    }

    public boolean isChangeEmailLinkIsDisplayed() {
        return !contactDetails.findElements(By.xpath(".//*[@id='email-address']/a")).isEmpty();
    }

    public boolean isDvsaRolesSectionIsDisplayed() {
        return !driver.findElements(By.id(DVSA_ROLES)).isEmpty();
    }

    public boolean isQualificationStatusSectionIsDisplayed() {
        return !driver.findElements(By.id(QUALIFICATION_STATUS)).isEmpty();
    }

    public boolean isDrivingLicenceAndDOBInformationIsDisplayed() {
        return !personalDetails.findElements(By.id("drivingLicence")).isEmpty() &&
                !personalDetails.findElements(By.id("dateOfBirth")).isEmpty();
    }

    public boolean isAccountSecuritySectionDisplayed() {
        return accountSecurity.isDisplayed();
    }

    public boolean isAccountManagementSectionDisplayed() {
        return accountManagement.isDisplayed();
    }

    public boolean isChangeQualificationLinksDisplayed() {
        return !related.findElements(By.id(GROUP_A_QUALIFICATION)).isEmpty() &&
                !related.findElements(By.id(GROUP_B_QUALIFICATION)).isEmpty();
    }
}
