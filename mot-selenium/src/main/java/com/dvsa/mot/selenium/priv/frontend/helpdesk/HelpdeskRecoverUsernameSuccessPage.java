package com.dvsa.mot.selenium.priv.frontend.helpdesk;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class HelpdeskRecoverUsernameSuccessPage extends BasePage {
    private static String PAGE_TITLE = "RECOVER USERNAME FOR";

    @FindBy(id = "back_to_user_profile") private WebElement backToUserProfile;

    @FindBy(id = "return_to_home") private WebElement returnToHome;


    @FindBy(id = "recover-username-confirmation") private WebElement recoverUsername;

    public HelpdeskRecoverUsernameSuccessPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public UserDashboardPage returnToHome() {
        returnToHome.click();
        return new UserDashboardPage(driver);
    }

    public String getSuccessfulResetUsernameMessage() {

        return recoverUsername.getText().trim();
    }


}
