package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpDeskUserProfilePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class RecordDemoPageGroupB extends BasePage {

    @FindBy(id = "confirm-record-demo-test") private WebElement confirmButton;

    @FindBy(id = "cancel-record-demo-test") private WebElement cancelLink;

    @FindBy(id = "radioStandard3") private WebElement qualifiedRadioButton;

    public RecordDemoPageGroupB(WebDriver driver){
        super(driver);
    }

    public QualificationChageConfirmationPage clickConfirm(){
        confirmButton.click();
        return new QualificationChageConfirmationPage(driver);
    }

    public HelpDeskUserProfilePage clickCancel(){
        cancelLink.click();
        return new HelpDeskUserProfilePage(driver);
    }

    public RecordDemoPageGroupA clickQualifiedRadioButton(){
        qualifiedRadioButton.click();
        return new RecordDemoPageGroupA(driver);
    }
}
