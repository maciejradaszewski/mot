package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class UserNamePage extends BasePage {

    private String PAGE_TITLE = "MOT TESTING SERVICE\n" + "FORGOTTEN PASSWORD\n" + "STEP 1 OF 3";

    public UserNamePage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    @FindBy(id = "username") private WebElement username;

    @FindBy(partialLinkText = "Forgotten user ID") private WebElement forgottenUserId;

    @FindBy(id = "submitUserId") private WebElement submitUserId;

    @FindBy(partialLinkText = "Back to login screen") private WebElement backToLoginScreen;

    @FindBy(className = "lede") private WebElement userAccountMessage;

    @FindBy(id = "userAccount") private WebElement userAccount;

    @FindBy(className = "validation-message") private WebElement validationMessage;

    public ForgotPwdSecurityQuesOnePage submitValidUserName(String userName) {
        username.clear();
        username.sendKeys(userName);
        submitUserId.click();
        return new ForgotPwdSecurityQuesOnePage(driver);
    }

    public UserNamePage submitInvalidUserName(String userName) {
        username.clear();
        username.sendKeys(userName);
        submitUserId.click();
        return new UserNamePage(driver);
    }

    public String getUserAccountMsg() {
        return userAccountMessage.getText();
    }

    public String getUserAccount() {
        return userAccount.getText();
    }

    public String getValidationMessage() { return  validationMessage.getText();}

}

