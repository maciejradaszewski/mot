package com.dvsa.mot.selenium.priv.frontend.helpdesk;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class HelpdeskResetPasswordUnsuccessPage extends BasePage {
    private String PAGE_TITLE = "UNABLE TO RESET PASSWORD FOR";

    @FindBy(id = "back_to_user_profile") private WebElement backToUserProfile;

    @FindBy(id = "return_to_home") private WebElement returnToHome;


    public HelpdeskResetPasswordUnsuccessPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public UserDashboardPage returnToHome() {
        returnToHome.click();
        return new UserDashboardPage(driver);
    }

}
