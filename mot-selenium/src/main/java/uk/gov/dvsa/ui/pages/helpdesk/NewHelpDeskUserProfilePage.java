package uk.gov.dvsa.ui.pages.helpdesk;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.StringContains.containsString;

public class NewHelpDeskUserProfilePage extends HelpDeskProfilePage {

    private static final String PAGE_TITLE = "User profile";
    public static final String PATH = "/user-admin/user/%s";

    @FindBy (css = "#email-address a") private WebElement changeEmailLink;
    @FindBy (id = "email-address") private WebElement emailAddress;
    @FindBy(id = "person-2fa-method") private WebElement personAuthenticationMethod;


    public NewHelpDeskUserProfilePage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }

    public ChangeEmailPage clickChangeUserEmailLink() {
        changeEmailLink.click();
        return new ChangeEmailPage(driver);
    }

    public String getEmail() {
        return emailAddress.getText();
    }

    public HelpDeskProfilePage updateEmailSuccessfully(String email) {
        clickChangeUserEmailLink().updateEmailSuccessfully(email);
        return new NewHelpDeskUserProfilePage(driver);
    }

    public boolean isEmailUpdateSuccessful(String email) {
        assertThat("Assert that the users Email was updated successfully", getEmail(),
                containsString(email));
        return true;
    }

    public boolean isPersonAuthenticationMethodIsDisplayed() {
        boolean isElementPresent;
        try {
            isElementPresent = personAuthenticationMethod.isDisplayed();
        } catch (Exception exception) {
            isElementPresent = false;
        }
        return isElementPresent;
    }
}
