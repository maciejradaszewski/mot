package com.dvsa.mot.selenium.priv.frontend.login.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class AuthorisationFailedPage extends BasePage {

    @FindBy(tagName = "h3") private WebElement authFailedTitle;

    private final String AUTH_FAILED_TITLE = "Authentication failed.";

    public AuthorisationFailedPage(WebDriver driver) {
        super(driver);
    }

    public boolean checkAuthFailedTitle() {
        return authFailedTitle.equals(AUTH_FAILED_TITLE);
    }


}
