package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;

import com.dvsa.mot.selenium.framework.BasePage;

public class ReasonForAdjustmentPage extends BasePage {
    
    private static final String PAGE_TITLE = "MANUAL ADJUSTMENT";
    
    @FindBy(id = "continue") private WebElement continueButton;

    public ReasonForAdjustmentPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }
    
    public ReasonForAdjustmentPage selectReasonForAdjustment(String reason) {
        Select dropDownBox = new Select(driver.findElement(By.id("inputReason")));
        dropDownBox.selectByVisibleText(reason);
        return new ReasonForAdjustmentPage(driver);
    }
    
    public EnterAdjustmentDetailsPage clickContinueButton() {
        continueButton.click();
        return new EnterAdjustmentDetailsPage(driver);
    }
    
}
