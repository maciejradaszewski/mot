package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import com.dvsa.mot.selenium.framework.BasePage;

public class ManualAdjustmentSuccessPage extends BasePage {
    
    private static final String PAGE_TITLE = "MANUAL ADJUSTMENT";
    
    @FindBy(id = "statusMessage") private WebElement statusMessage;

    public ManualAdjustmentSuccessPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }
    
    public String getAdjustmentStatusMessage() {
        return statusMessage.getText();
    }

}
