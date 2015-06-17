package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class AssessmentDetailsConfirmationPage extends BasePage{

    public AssessmentDetailsConfirmationPage(WebDriver driver){
        super(driver);
    }

    @FindBy(className = "content-header__title")
    private WebElement contentTitle;

    @FindBy(className = "message--success")
    private WebElement messageSuccess;

    public String getContentTitle(){
        return  contentTitle.getText();
    }

    public String getSuccessMsg(){
        return messageSuccess.getText();
    }
}
