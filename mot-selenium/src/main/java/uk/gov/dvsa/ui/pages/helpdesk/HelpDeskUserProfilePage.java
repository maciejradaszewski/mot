package uk.gov.dvsa.ui.pages.helpdesk;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.StringContains.containsString;

public class HelpDeskUserProfilePage extends Page {

    private static final String PAGE_TITLE = "User profile";
    public static final String PATH = "/user-admin/user-profile/%s";

    @FindBy (id = "email-address-change") private WebElement changeEmailLink;
    @FindBy (id = "person-email") private WebElement emailAddress;
    @FindBy(id = "person-2fa-method") private WebElement personAuthenticationMethod;

    public HelpDeskUserProfilePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public ChangeEmailPage clickChangeUserEmailLink() {
        changeEmailLink.click();
        return new ChangeEmailPage(driver);
    }

    public String getEmail() {
        return emailAddress.getText();
    }

    public HelpDeskUserProfilePage updateEmailSuccessfully(String email) {
        clickChangeUserEmailLink().updateEmailSuccessfully(email);
        return new HelpDeskUserProfilePage(driver);
    }

    public boolean isEmailUpdateSuccessful(String email) {
        assertThat("Assert that the users Email was updated successfully", getEmail(),
                containsString(email));
        return true;
    }

    public boolean isPersonAuthenticationMethodIsDisplayed() {
        return personAuthenticationMethod.isDisplayed();
    }
}
