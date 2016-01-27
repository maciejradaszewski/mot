package uk.gov.dvsa.ui.pages.profile;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.ProfilePage;
import uk.gov.dvsa.ui.pages.ChangePasswordFromProfilePage;
import uk.gov.dvsa.ui.pages.dvsa.RolesAndAssociationsPage;

public class PersonProfilePage extends ProfilePage {

    public static final String PATH = "/profile";

    @FindBy(id = "full-address") private WebElement addressField;
    @FindBy(id = "email-address") private WebElement emailAddressField;
    @FindBy(id = "tester-qualification-status") private WebElement qualificationStatus;
    @FindBy(id = "change-password") private WebElement changePasswordLink;
    @FindBy(id = "validation-message--success") private WebElement messageSuccess;
    @FindBy(id="roles-and-associations-link") private WebElement rolesAndAssociationsLink;

    public PersonProfilePage(MotAppDriver driver) {
        super(driver, driver.getCurrentUser().getFullName());
    }

    public boolean verifyPostCodeIsChanged(String postcode) {
        return addressField.getText().contains(postcode);
    }

    public boolean verifyEmailIsChanged(String email) {
        return emailAddressField.getText().equals(email);
    }

    @Override
    public boolean isTesterQualificationStatusDisplayed() {
        return qualificationStatus.isDisplayed();
    }

    public ChangePasswordFromProfilePage clickChangePasswordLink() {
        changePasswordLink.click();
        return new ChangePasswordFromProfilePage(driver);
    }

    @Override
    public RolesAndAssociationsPage clickRolesAndAssociationsLink() {
        rolesAndAssociationsLink.click();
        return new RolesAndAssociationsPage(driver);
    }

    public boolean isSuccessMessageDisplayed(){
        return messageSuccess.isDisplayed();
    }

    public String getMessageSuccess(){
       return messageSuccess.getText();
    }

    @Override
    public boolean isRolesAndAssociationsLinkDisplayed() {
        return PageInteractionHelper.isElementDisplayed(rolesAndAssociationsLink);
    }
}
