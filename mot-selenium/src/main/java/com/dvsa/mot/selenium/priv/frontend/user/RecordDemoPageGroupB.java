package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpDeskUserProfilePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

/**
 * Created by annas on 26/06/2015.
 */
public class RecordDemoPageGroupB extends BasePage {

    @FindBy(id = "confirm-record-demo-test") private WebElement confirmButton;

    @FindBy(id = "cancel-record-demo-test") private WebElement cancelLink;

    public RecordDemoPageGroupB(WebDriver driver){
        super(driver);
    }

    public HelpDeskUserProfilePage clickConfirm(){
        confirmButton.click();
        return new HelpDeskUserProfilePage(driver);
    }

    public HelpDeskUserProfilePage clickCancel(){
        cancelLink.click();
        return new HelpDeskUserProfilePage(driver);
    }
}
