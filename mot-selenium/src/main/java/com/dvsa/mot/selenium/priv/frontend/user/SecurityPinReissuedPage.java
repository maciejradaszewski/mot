package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

/**
 * Created by davidd on 18/12/14.
 */
public class SecurityPinReissuedPage extends BasePage {

    @FindBy(id = "second-auth-pin") private WebElement pin;

    @FindBy(id = "reset-pin") private WebElement resetPin;

    @FindBy(partialLinkText = "Cancel and return to your profile") private WebElement userHomeLink;

    public SecurityPinReissuedPage(WebDriver driver) {
        super(driver);
        checkTitle(PageTitles.RESET_PIN.getPageTitle());
    }

    public String getNewPin() {
        return pin.getText().trim();
    }

    public static SecurityPinReissuedPage navigateHereFromLoginPage(WebDriver driver, Login login) {
        return UserPersonalProfilePage.navigateHereFromLoginPage(driver, login)
                .clickResetPinLink()
                .submitValidAnswer(Text.TEXT_SECURITY_ANSWER_1, PageTitles.RESET_PIN.getPageTitle())
                .submitValidAnswer(Text.TEXT_SECURITY_ANSWER_2, PageTitles.RESET_PIN.getPageTitle())
                .clickResetPin();
    }

}

