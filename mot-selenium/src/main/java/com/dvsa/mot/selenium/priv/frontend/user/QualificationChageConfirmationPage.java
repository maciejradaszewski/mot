package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpDeskUserProfilePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class QualificationChageConfirmationPage extends BasePage {

    @FindBy(id = "confirm-record-demo-test") private WebElement confirmButton;

    public QualificationChageConfirmationPage(WebDriver driver) {
        super(driver);
    }

    public HelpDeskUserProfilePage clickConfirm(){
        confirmButton.click();
        return new HelpDeskUserProfilePage(driver);
    }
}