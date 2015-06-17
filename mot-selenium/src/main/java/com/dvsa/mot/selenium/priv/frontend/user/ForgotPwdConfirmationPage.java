package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ForgotPwdConfirmationPage extends BasePage {

    private String PAGE_TITLE =
            "MOT TESTING SERVICE\n" + "SUCCESS!\n" + "USER AUTHENTICATED AND EMAIL SENT";

    @FindBy(className = "col-lg-12") private WebElement emailConfirmationMsg;

    @FindBy(id = "validation-summary-message") private WebElement emailValidationMsg;

    public ForgotPwdConfirmationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getEmailConfirmationMsg() {
        return emailConfirmationMsg.getText();
    }

    public String getEmailValidationMsg() {
        return emailValidationMsg.getText();
    }
}
