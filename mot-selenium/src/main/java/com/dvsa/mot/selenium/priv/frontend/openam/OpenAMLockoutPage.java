package com.dvsa.mot.selenium.priv.frontend.openam;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.ForgottenPasswordPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class OpenAMLockoutPage extends BasePage {

    @FindBy(linkText = "change your password") private WebElement changePasswordLink;

    private final String PAGE_TITLE = "YOUR ACCOUNT HAS BEEN LOCKED";

    public OpenAMLockoutPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public ForgottenPasswordPage clickOnChangePasswordLink() {
        changePasswordLink.click();
        return new ForgottenPasswordPage(driver);
    }


}
