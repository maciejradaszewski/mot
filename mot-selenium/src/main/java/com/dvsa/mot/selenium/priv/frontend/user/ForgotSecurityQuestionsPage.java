package com.dvsa.mot.selenium.priv.frontend.user;


import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ForgotSecurityQuestionsPage extends BasePage {

    private String PAGE_TITLE =
            "MOT TESTING SERVICE\n" + "FORGOTTEN SECURITY QUESTION(S)\n" + "CALL HELPDESK";

    @FindBy(className = "lede") private WebElement msgToUser;

    public ForgotSecurityQuestionsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getMsgToUser(){
        return msgToUser.getText();
    }
}
