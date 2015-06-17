package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class UserPersonalProfilePage extends BasePage {

    @FindBy(id = "edit-user-profile") private WebElement editUserProfile;

    @FindBy(id = "display-name") private WebElement userDisplayName;

    @FindBy(id = "drivingLicence") private WebElement userDrivingLicenceNumber;

    @FindBy(id = "addressLine2") private WebElement userAddress2;

    @FindBy(id = "postcode") private WebElement userPostCode;

    @FindBy(id = "email-address") private WebElement email;

    @FindBy(id = "homepage") private WebElement homePage;

    @FindBy(id = "initial-training-successful") private WebElement successfulButton;

    @FindBy(id = "initial-training-fail") private WebElement unSuccessfulButton;

    @FindBy(id = "display-role") private WebElement displayRoleText;

    @FindBy(id = "change-security-settings") private WebElement changeSecuritySettingsLink;

    public UserPersonalProfilePage(WebDriver driver) {
        super(driver);
        checkTitle(PageTitles.PERSONAL_PROFILE_TITLE.getPageTitle());
    }

    public static UserPersonalProfilePage navigateHereFromLoginPage(WebDriver driver, Login login) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).clickMyProfile();
    }

    public EditPersonalProfilePage editUserProfile() {
        this.editUserProfile.click();
        return new EditPersonalProfilePage(driver);
    }

    public String getEmail() {
        return email.getText();
    }

    public ForgotPwdSecurityQuesOnePage clickResetPinLink() {
        changeSecuritySettingsLink.click();
        return new ForgotPwdSecurityQuesOnePage(driver,PageTitles.RESET_PIN.getPageTitle());
    }

}
